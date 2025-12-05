const GetProductsByUser = require('../../../usecases/product/getProductsByUser');
const ProductRepository = require('../../repositories/productRepository');
const ProductInformation = require('../../../dto/product/productInformation');
const ImageProductInformation = require('../../../dto/imageProduct/imageProductInformation');
const { InternalServerError } = require('../../../domain/errors');

/**
 * Controller untuk mendapatkan produk berdasarkan user yang login
 */
async function GetProductsByUserController(req, res) {
    try {
        console.log('GetProductsByUserController called');
        console.log('Authenticated user:', req.user);

        const userId = req.user.userId;

        if (!userId) {
            return res.status(401).json({
                success: false,
                message: 'User not authenticated'
            });
        }

        const productRepository = new ProductRepository();
        const getProductsByUser = new GetProductsByUser(productRepository);

        const result = await getProductsByUser.execute(userId);

        // Transform using DTO (same as GetAllProductsController)
        const productsDTO = result.products.map(product => {
            const productDTO = new ProductInformation(product);
            productDTO.images = product.images.map(image => new ImageProductInformation(image));
            productDTO.shop_name = product.shop_name;
            productDTO.review_count = product.review_count;
            productDTO.average_rating = parseFloat(product.average_rating);
            return productDTO;
        });

        return res.status(200).json({
            success: true,
            message: 'Products retrieved successfully',
            data: {
                products: productsDTO,
                total: result.total
            }
        });

    } catch (error) {
        console.error('GetProductsByUserController Error:', error);
        
        if (error instanceof InternalServerError) {
            return res.status(500).json({
                success: false,
                message: error.message
            });
        }

        return res.status(500).json({
            success: false,
            message: 'Failed to retrieve products',
            error: error.message
        });
    }
}

module.exports = GetProductsByUserController;