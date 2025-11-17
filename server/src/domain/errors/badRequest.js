class BadRequest extends Error {
  constructor(message = "Bad request") {
    super(message);
    this.name = "BadRequest";
    this.statusCode = 400;
  }
}

module.exports = BadRequest;
