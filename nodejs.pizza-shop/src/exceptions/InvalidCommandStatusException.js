export class InvalidCommandStatusException extends Error {  
  constructor (message, statuscode) {
    super(message)
    Error.captureStackTrace(this, this.constructor);

    this.name = this.constructor.name;
    this.status = statuscode || 400;
  }

  statusCode() {
    return this.status;
  }
}