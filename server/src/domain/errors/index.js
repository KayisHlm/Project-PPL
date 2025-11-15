const AlreadyExist = require("./alreadyExist");
const BadRequest = require("./badRequest");
const CredentialNotFulfilled = require("./credentialNotFulfilled");
const Forbidden = require("./forbidden");
const NotFound = require("./notFound");
const InternalServerError = require("./internalServerError");
const UnprocessableEntity = require("./unprocessableEntity");

module.exports = {
  AlreadyExist,
  BadRequest,
  CredentialNotFulfilled,
  Forbidden,
  NotFound,
  InternalServerError,
  UnprocessableEntity
};
