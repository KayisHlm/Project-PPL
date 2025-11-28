const { NotFound } = require("../../domain/errors");

class RejectSeller {
    constructor(sellerRepository, mailer) {
        this.sellerRepository = sellerRepository;
        this.mailer = mailer;
    }

    async execute(sellerId, reason = null) {
        try {
            // Cek apakah seller exists
            const seller = await this.sellerRepository.findById(sellerId);
            
            if (!seller) {
                throw new NotFound("Seller not found");
            }

            // Update status menjadi 'rejected'
            const updatedSeller = await this.sellerRepository.updateStatus(
                sellerId, 
                'rejected', 
                null,
                reason
            );

            const sellerForEmail = {
                ...seller,
                ...updatedSeller
            };

            // Kirim email notifikasi penolakan jika mailer tersedia
            try {
                if (this.mailer && this.mailer.sendRejectionEmail) {
                    await this.mailer.sendRejectionEmail(sellerForEmail, reason);
                }
            } catch (emailErr) {
                console.error('RejectSeller: failed to send rejection email', emailErr);
                // Tidak menggagalkan update status jika email gagal
            }

            return updatedSeller;

        } catch (error) {
            console.error("RejectSeller Use Case Error:", error);
            throw error;
        }
    }
}

module.exports = RejectSeller;