class GetPendingSellers {
    constructor(sellerRepository) {
        this.sellerRepository = sellerRepository;
    }

    async execute() {
        try {
            console.log('GetPendingSellers.execute() called');
            
            const sellers = await this.sellerRepository.findPendingSellers();
            
            console.log(`Found ${sellers.length} pending sellers`);

            const transformedSellers = sellers.map(seller => ({
                id: seller.id, 
                userId: seller.user_id,
                userEmail: seller.user_email,
                shopName: seller.shop_name,
                shopDescription: seller.shop_description,
                picName: seller.pic_name,
                picPhone: seller.pic_phone_number,
                picEmail: seller.pic_email,
                picKtp: seller.pic_ktp_number,
                picAddress: seller.pic_address,
                picRt: seller.pic_rt,
                picRw: seller.pic_rw,
                picProvince: seller.pic_province,
                picCity: seller.pic_city,
                picDistrict: seller.pic_district,
                picVillage: seller.pic_village,
                picPhotoPath: seller.pic_photo_path,
                picKtpPath: seller.pic_ktp_path,
                status: seller.status,
                verifiedAt: seller.verified_at,
                createdAt: seller.created_at,
                updatedAt: seller.updated_at
            }));

            return {
                sellers: transformedSellers,
                total: transformedSellers.length
            };
        } catch (error) {
            console.error('Error in GetPendingSellers:', error);
            throw error;
        }
    }
}

module.exports = GetPendingSellers;