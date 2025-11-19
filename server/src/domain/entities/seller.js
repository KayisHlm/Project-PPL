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
        picVillage,
        picCity,
        picProvince,
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
        this.picVillage = picVillage;
        this.picCity = picCity;
        this.picProvince = picProvince;
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
