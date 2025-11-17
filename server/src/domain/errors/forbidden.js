class Forbidden extends Error {
  constructor(message = "Forbidden access") {
    super(message);
    this.name = "Forbidden";
    this.statusCode = 403;
  }
}

module.exports = Forbidden;
