const GetProductsByUser = require('../../../usecases/product/getProductsByUser');
const ProductRepository = require('../../repositories/productRepository');
const ProductCompleteInformation = require('../../../dto/product/productCompleteInformation');
const { InternalServerError } = require('../../../domain/errors');

/**
 * Controller untuk mendapatkan produk berdasarkan user yang login
 * Returns products dengan detail lengkap termasuk images, reviews, shop info, dan statistics
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

        // Map ke DTO yang sudah handle semua field secara otomatis
        const productsDTO = result.products.map(product => 
            new ProductCompleteInformation(product)
        );

        console.log(`Retrieved ${productsDTO.length} products for user ${userId}`);

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