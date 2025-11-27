const CreateReview = require("../../../usecases/review/createReview");
const ReviewRepository = require("../../repositories/reviewRepository");
const ProductRepository = require("../../repositories/productRepository");
const ReviewInformation = require("../../../dto/review/reviewInformation");

// Helper function to map database row to camelCase
function mapReviewToDTO(review) {
  if (!review) return null;
  return {
    id: review.id,
    productId: review.product_id,
    email: review.email,
    name: review.name,
    noTelp: review.no_telp,
    rating: parseInt(review.rating, 10),
    comment: review.comment,
    createdAt: review.created_at,
    updatedAt: review.updated_at
  };
}

async function create(req, res) {
  try {
    const productId = req.params.productId;
    if (!productId) {
      return res.status(400).json({ code: 400, message: "Product ID is required" });
    }

    const usecase = new CreateReview(new ReviewRepository(), new ProductRepository());
    const review = await usecase.execute(productId, req.body);
    const reviewDTO = mapReviewToDTO(review);
    return res.status(201).json({ code: 201, message: "Review created", review: reviewDTO });
  } catch (error) {
    if (error.statusCode) {
      return res.status(error.statusCode).json({ code: error.statusCode, message: error.message });
    }
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function listByProduct(req, res) {
  try {
    const productId = req.params.productId;
    if (!productId) {
      return res.status(400).json({ code: 400, message: "Product ID is required" });
    }

    const repo = new ReviewRepository();
    const reviews = await repo.listByProduct(productId);
    const reviewsDTO = reviews.map(mapReviewToDTO);
    return res.status(200).json({ code: 200, data: reviewsDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function listByEmail(req, res) {
  try {
    const email = req.query.email;
    if (!email) {
      return res.status(400).json({ code: 400, message: "Email query parameter is required" });
    }

    const repo = new ReviewRepository();
    const reviews = await repo.listByEmail(email);
    const reviewsDTO = reviews.map(mapReviewToDTO);
    return res.status(200).json({ code: 200, data: reviewsDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function listAll(req, res) {
  try {
    const repo = new ReviewRepository();
    const reviews = await repo.listAll();
    const reviewsDTO = reviews.map(mapReviewToDTO);
    return res.status(200).json({ code: 200, data: reviewsDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function getById(req, res) {
  try {
    const reviewId = req.params.id;
    if (!reviewId) {
      return res.status(400).json({ code: 400, message: "Review ID is required" });
    }

    const repo = new ReviewRepository();
    const review = await repo.findById(reviewId);
    if (!review) {
      return res.status(404).json({ code: 404, message: "Review not found" });
    }

    const reviewDTO = mapReviewToDTO(review);
    return res.status(200).json({ code: 200, data: reviewDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function update(req, res) {
  try {
    const reviewId = req.params.id;
    if (!reviewId) {
      return res.status(400).json({ code: 400, message: "Review ID is required" });
    }

    const repo = new ReviewRepository();
    const existingReview = await repo.findById(reviewId);
    if (!existingReview) {
      return res.status(404).json({ code: 404, message: "Review not found" });
    }

    // Authorization: Only the review owner (by email) can update their review
    const userEmail = req.user && req.user.email ? req.user.email : req.body.email;
    if (!userEmail || existingReview.email !== userEmail) {
      return res.status(403).json({ code: 403, message: "Forbidden: You can only update your own reviews" });
    }

    // Validate rating if provided
    if (req.body.rating) {
      const rating = parseInt(req.body.rating, 10);
      if (isNaN(rating) || rating < 1 || rating > 5) {
        return res.status(400).json({ code: 400, message: "rating must be an integer between 1 and 5" });
      }
    }

    const updated = await repo.update(reviewId, req.body);
    
    // Update product rating after review update
    const productRepo = new ProductRepository();
    const { avgRating } = await repo.getAverageRating(existingReview.product_id);
    await productRepo.updateRating(existingReview.product_id, avgRating);

    const reviewDTO = mapReviewToDTO(updated);
    return res.status(200).json({ code: 200, message: "Review updated", review: reviewDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function remove(req, res) {
  try {
    const reviewId = req.params.id;
    if (!reviewId) {
      return res.status(400).json({ code: 400, message: "Review ID is required" });
    }

    const repo = new ReviewRepository();
    const existingReview = await repo.findById(reviewId);
    if (!existingReview) {
      return res.status(404).json({ code: 404, message: "Review not found" });
    }

    // Authorization: Only the review owner (by email) can delete their review
    const userEmail = req.user && req.user.email ? req.user.email : req.body.email;
    if (!userEmail || existingReview.email !== userEmail) {
      return res.status(403).json({ code: 403, message: "Forbidden: You can only delete your own reviews" });
    }

    const productId = existingReview.product_id;
    await repo.delete(reviewId);

    // Update product rating after review deletion
    const productRepo = new ProductRepository();
    const { avgRating } = await repo.getAverageRating(productId);
    await productRepo.updateRating(productId, avgRating);

    return res.status(200).json({ code: 200, message: "Review deleted successfully" });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function getProductStats(req, res) {
  try {
    const productId = req.params.productId;
    if (!productId) {
      return res.status(400).json({ code: 400, message: "Product ID is required" });
    }

    const repo = new ReviewRepository();
    const { avgRating, totalReviews } = await repo.getAverageRating(productId);
    const distribution = await repo.getRatingDistribution(productId);

    return res.status(200).json({ 
      code: 200, 
      data: {
        averageRating: avgRating,
        totalReviews,
        ratingDistribution: distribution
      }
    });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

module.exports = { 
  create, 
  listByProduct, 
  listByEmail, 
  listAll, 
  getById, 
  update, 
  remove,
  getProductStats
};
