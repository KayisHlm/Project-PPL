class Seller {
    constructor({
        id,
        user_id,
        shop_name,
        shop_description,
        pic_name,
        pic_phone_number,
        pic_email,
        pic_address,
        pic_rt,
        pic_rw,
        pic_village,
        pic_city,
        pic_province,
        pic_ktp_number,
        pic_photo_path,
        pic_ktp_path,
        status,
        rejection_reason,
        verified_at,
        created_at,
        updated_at
    }) {
        this.id = id;
        this.user_id = user_id;
        this.shop_name = shop_name;
        this.shop_description = shop_description;
        this.pic_name = pic_name;
        this.pic_phone_number = pic_phone_number;
        this.pic_email = pic_email;
        this.pic_address = pic_address;
        this.pic_rt = pic_rt;
        this.pic_rw = pic_rw;
        this.pic_village = pic_village;
        this.pic_city = pic_city;
        this.pic_province = pic_province;
        this.pic_ktp_number = pic_ktp_number;
        this.pic_photo_path = pic_photo_path;
        this.pic_ktp_path = pic_ktp_path;
        this.status = status;
        this.rejection_reason = rejection_reason;
        this.verified_at = verified_at;
        this.created_at = created_at;
        this.updated_at = updated_at;
    }
}

module.exports = Seller;