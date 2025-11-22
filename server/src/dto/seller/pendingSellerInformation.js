class PendingSellerInformation {
    constructor(seller) {
        this.id = seller.id;
        this.userId = seller.user_id;
        this.email = seller.user?.email || null;
        
        // Shop Information
        this.shopName = seller.shop_name;
        this.shopDescription = seller.shop_description;
        
        // PIC Information
        this.picName = seller.pic_name;
        this.picPhone = seller.pic_phone;
        this.picEmail = seller.pic_email;
        this.picKtp = seller.pic_ktp;
        
        // Address
        this.address = seller.address;
        this.provinceId = seller.province_id;
        this.cityId = seller.city_id;
        this.districtId = seller.district_id;
        this.villageId = seller.village_id;
        this.postalCode = seller.postal_code;
        
        // Status
        this.status = seller.status;
        this.verifiedAt = seller.verified_at;
        
        // Timestamps
        this.createdAt = seller.created_at;
        this.updatedAt = seller.updated_at;
    }
}

module.exports = PendingSellerInformation;