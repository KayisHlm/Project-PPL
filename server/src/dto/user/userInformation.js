class UserInformation {
    constructor (data) {
        const { user, seller } = data;
        this.email = user.email;
        this.password = user.password;
        this.shopName = seller.shopName;
        this.shopDescription = seller.shopDescription;
        this.picName = seller.picName;
        this.picPhoneNumber = seller.picPhoneNumber;
        this.picEmail = seller.picEmail;
        this.picAddress = seller.picAddress;
        this.picRt = seller.picRt;
        this.picRw = seller.picRw;
        this.picProvince = seller.picProvince;
        this.picCity = seller.picCity;
        this.picDistrict = seller.picDistrict;
        this.picVillage = seller.picVillage;
        this.picKtpNumber = seller.picKtpNumber;
        this.picPhotoPath = seller.picPhotoPath;
        this.picKtpPath = seller.picKtpPath;
    }


    
    toJSON() {
        return {
            email: this.email,
            shopName: this.shopName,
            shopDescription: this.shopDescription,
            picName: this.picName,
            picPhoneNumber: this.picPhoneNumber,
            picEmail: this.picEmail,
            picAddress: this.picAddress,
            picRt: this.picRt,
            picRw: this.picRw,
            picProvince: this.picProvince,
            picCity: this.picCity,
            picDistrict: this.picDistrict,
            picVillage: this.picVillage,
            picKtpNumber: this.picKtpNumber,
            picPhotoPath: this.picPhotoPath,
            picKtpPath: this.picKtpPath
        };
    }
}

module.exports = UserInformation;