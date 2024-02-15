export const errorAction = (err, req, res, next) => {
    let status = 500;
    let message = 'Internal Server Error';
    let exception = [];

    if (err.status) {
        status = err.status;
        message = err.message || 'Unknown Error';
        exception.push({
            type: err.constructor.name,
            code: status,
            file: err.fileName || err.sourceURL || 'unknown',
            line: err.lineNumber || 'unknown',
        });
    }

    res.status(status).json({
        status: 'error',
        message: message,
        exception,
    });
};