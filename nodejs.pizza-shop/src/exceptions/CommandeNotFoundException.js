export class CommandeNotFoundException extends Error {  
  constructor (message, statuscode) {
    super(message)
    Error.captureStackTrace(this, this.constructor);

    this.name = this.constructor.name;
    this.status = statuscode || 404;
  }

  statusCode() {
    return this.status;
  }
}