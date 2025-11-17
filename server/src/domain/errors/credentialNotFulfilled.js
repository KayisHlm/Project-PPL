class CredentialNotFulfilled extends Error {
  constructor(message = "Credentials not fulfilled") {
    super(message);
    this.name = "CredentialNotFulfilled";
    this.statusCode = 401;
  }
}

module.exports = CredentialNotFulfilled;
