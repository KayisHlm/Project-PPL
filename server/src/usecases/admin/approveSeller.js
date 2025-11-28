const { NotFound } = require("../../domain/errors");

class ApproveSeller {
    constructor(sellerRepository, mailer) {
        this.sellerRepository = sellerRepository;
        this.mailer = mailer;
    }

    async execute(sellerId) {
        try {
            // Cek apakah seller exists
            const seller = await this.sellerRepository.findById(sellerId);
            
            if (!seller) {
                throw new NotFound("Seller not found");
            }

            // Update status menjadi 'approved'
            const updatedSeller = await this.sellerRepository.updateStatus(
                sellerId, 
                'approved', 
                new Date()
            );

            const sellerForEmail = {
                ...seller,
                ...updatedSeller
            };

            // Kirim email notifikasi persetujuan jika mailer tersedia
            try {
                if (this.mailer && this.mailer.sendApprovalEmail) {
                    await this.mailer.sendApprovalEmail(sellerForEmail);
                }
            } catch (emailErr) {
                console.error('ApproveSeller: failed to send approval email', emailErr);
                // Jangan gagalkan perubahan status hanya karena email gagal
            }

            return updatedSeller;

        } catch (error) {
            console.error("ApproveSeller Use Case Error:", error);
            throw error;
        }
    }
}

module.exports = ApproveSeller;