
var config = null;  
var privateKey, selectedPrinter;
    
    $.get('/privateKey', {"_token":  $('meta[name=csrf-token]').attr('content')}, function (res) {
        var response = res.response; 

        if (response == 'success') {
            privateKey = res.privateKey;
            selectedPrinter = res.printer;
            initializePrinter();
        }
    })

    function initializePrinter() {
        if (qz.websocket.isActive()) {
            console.log('printer is active')
            $('.printerStatus span').html('<b>Active!</b>')
        }
        else {
            console.log('connecting printer')
            qz.websocket.connect().then(function () {
                $('.printerStatus span').html('<b>Active!</b>')

                return qz.printers.find(selectedPrinter);               // Pass the printer name into the next Promise
            }).then(function (printer) {
                console.log(printer);
                config = qz.configs.create(printer);       // Create a default config for the found printer
                console.log(config);
            }).catch(e => {
                $('.printerStatus span').html('<b>Inctive!</b>')
                var message = "Printer Connection Error! : There was an issue connecting with the printer."
                $('.errorMsg').removeClass('hidden').find('ul').append('<li>'+message+'</li>', '<li>'+e.message+'</li>' );
                console.log(e.message);
            });
        }
    }

    qz.security.setCertificatePromise(function(resolve, reject) {

        resolve("-----BEGIN CERTIFICATE----- \n" +
    "MIIDljCCAn4CCQD8dOo9GPYgzzANBgkqhkiG9w0BAQsFADCBjDELMAkGA1UEBhMC\n" +
    "Q0ExCzAJBgNVBAgMAk9OMRAwDgYDVQQHDAdUT1JPTlRPMREwDwYDVQQKDAhTVEVS\n" +
    "SUxPRzERMA8GA1UECwwITE9DQUxERVYxEjAQBgNVBAMMCWxvY2FsaG9zdDEkMCIG\n" +
    "CSqGSIb3DQEJARYVa2F6aWFobWVkOTFAZ21haWwuY29tMB4XDTE4MDQwOTE4NDA0\n" +
    "MFoXDTQ5MTAwMjE4NDA0MFowgYwxCzAJBgNVBAYTAkNBMQswCQYDVQQIDAJPTjEQ\n" +
    "MA4GA1UEBwwHVE9ST05UTzERMA8GA1UECgwIU1RFUklMT0cxETAPBgNVBAsMCExP\n" +
    "Q0FMREVWMRIwEAYDVQQDDAlsb2NhbGhvc3QxJDAiBgkqhkiG9w0BCQEWFWthemlh\n" +
    "aG1lZDkxQGdtYWlsLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEB\n" +
    "AM5mqEmL6J9Y7Fcgv3E+6JG521h8pLEaQTYwHqACQrSYqktn9kCcBV9ig0pXGS5b\n" +
    "dlHeV5rv3Sa5J9m6cDcNGlouP62Xf76JKfLolXi7U8xvOZB2icOHoI/+VXk57C9k\n" +
    "wEQByXYXPinw+017zCM2M8uY6th7FSF6xL5fIzEC/YQ3Cj553e/PK1OMjZ145kct\n" +
    "qguf61Qe7qgZMZ0L9XbI2Okmbn2tO4K2UTy//Mx/6cdH8VtZUlmmz/+UOp9IbGxC\n" +
    "g6Ald2Y6kG9Z6aJ7oZzDACkiDjhtzlIhkvTN9fi+26W2O9mqmCe18iUIlEg79dlO\n" +
    "iTxfw0/UGjXd0FPd96weRtsCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAnpo6ecJf\n" +
    "9pMUbUQMAUGLP2kL39kQKc1Mb+hzbgvvXKyTDQHH7LFD9I8W8/ssYL/ROaJkaM/x\n" +
    "7lyMMlKhgI+pCUdgpZfVGheAyKhB3fHukoHNIIZGq1+tI5DyKzXM8sVQIcICZOXX\n" +
    "gwRrAXpOI3oUMb1KAJIHaqv4a+GbgkjOEhGc+xtWrphuov1BWNglVn8so7JI9r96\n" +
    "rsiYkMgvi9kPK4/eCD1HTrP6Sqv1uINZfLad3p3JfqDzqEE35fI4lFnxicQjcvC7\n" +
    "PqYJc3oPkchWmpUciCxASJTEONfzzT9YIWFn4BEJ1IfxJevIYzY2EzIngi5AI9Y8\n" +
    "lbPMmxvuLOgEvg==\n" +
    "-----END CERTIFICATE-----\n");
    });


    qz.security.setSignaturePromise(function (toSign) {
        return function (resolve, reject) {
            try {
                var pk = KEYUTIL.getKey(privateKey);
                var sig = new KJUR.crypto.Signature({"alg": "SHA1withRSA"});
                sig.init(pk); 
                sig.updateString(toSign);
                var hex = sig.sign();
                console.log("DEBUG: \n\n" + stob64(hextorstr(hex)));
                resolve(stob64(hextorstr(hex)));
            } catch (err) {
                console.error(err);
                reject(err);
                $('.printerStatus span').html('<b>Inctive!</b>')
                var message = "Printer Error! : The system was unable to establish a security signature!"
                $('.errorMsg').removeClass('hidden').find('ul').append('<li>'+message+'</li>');
            }
        };
    });

    qz.configs.setDefaults({
        size: {
            width: 50.8, 
            height: 25.4
        }, 
        units: 'mm', 
        orientation: 'landscape'
    })

    function strip(key) {
        if (key.indexOf('-----') !== -1) {
            return key.split('-----')[2].replace(/\r?\n|\r/g, '');
        }
    }