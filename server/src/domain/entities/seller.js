class Seller {
    constructor({
        id,
        userId,
        shopName,
        shopDescription,
        picName,
        picPhoneNumber,
        picEmail,
        picAddress,
        picRt,
        picRw,
        picProvince,
        picCity,
        picDistrict,
        picVillage,
        picKtpNumber,
        picPhotoPath,
        picKtpPath,
        status,
        rejectionReason,
        verifiedAt,
        createdAt,
        updatedAt
    }) {
        this.id = id;
        this.userId = userId;
        this.shopName = shopName;
        this.shopDescription = shopDescription;
        this.picName = picName;
        this.picPhoneNumber = picPhoneNumber;
        this.picEmail = picEmail;
        this.picAddress = picAddress;
        this.picRt = picRt;
        this.picRw = picRw;
        this.picProvince = picProvince;
        this.picCity = picCity;
        this.picDistrict = picDistrict;
        this.picVillage = picVillage;
        this.picKtpNumber = picKtpNumber;
        this.picPhotoPath = picPhotoPath;
        this.picKtpPath = picKtpPath;
        this.status = status;
        this.rejectionReason = rejectionReason;
        this.verifiedAt = verifiedAt;
        this.createdAt = createdAt;
        this.updatedAt = updatedAt;
    }
}

module.exports = Seller;
