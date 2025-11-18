class UserInformation {
    constructor (user) {
        this.email = user.email;
        this.password = user.password;
        this.shopName = user.shopName;
        this.shopDescription = user.shopDescription;
        this.picName = user.picName;
        this.picPhoneNumber = user.picPhoneNumber;
        this.picEmail = user.picEmail;
        this.picAddress = user.picAddress;
        this.picRt = user.picRt;
        this.picRw = user.picRw;
        this.picVillage = user.picVillage;
        this.picCity = user.picCity;
        this.picProvince = user.picProvince;
        this.picKtpNumber = user.picKtpNumber;
        this.picPhotoPath = user.picPhotoPath;
        this.picKtpPath = user.picKtpPath;
    }

    toJSON() {
        return {
            email: this.email,
            password: this.password,
            shopName: this.shopName,
            shopDescription: this.shopDescription,
            picName: this.picName,
            picPhoneNumber: this.picPhoneNumber,
            picEmail: this.picEmail,
            picAddress: this.picAddress,
            picRt: this.picRt,
            picRw: this.picRw,
            picVillage: this.picVillage,
            picCity: this.picCity,
            picProvince: this.picProvince,
            picKtpNumber: this.picKtpNumber,
            picPhotoPath: this.picPhotoPath,
            picKtpPath: this.picKtpPath
        };
    }
}

module.exports = UserInformation;