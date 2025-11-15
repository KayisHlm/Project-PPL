class AlreadyExist extends Error {
  constructor(message = "Resource already exists") {
    super(message);
    this.name = "AlreadyExist";
    this.statusCode = 409; // Conflict
  }
}

module.exports = AlreadyExist;
