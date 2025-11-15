// untuk validasi input yang gagal (field tidak sesuai format, dll)
class UnprocessableEntity extends Error {
  constructor(message = "Unprocessable entity", errors = null) {
    super(message);
    this.name = "UnprocessableEntity";
    this.statusCode = 422;
    this.errors = errors; // detail validasi
  }
}

module.exports = UnprocessableEntity;
