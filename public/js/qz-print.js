
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
                $('.printerStatus span').html('<b>Inactive!</b>')
                var message = "Printer Connection Error! : There was an issue connecting with the printer."
                $('.errorMsg').removeClass('hidden').find('ul').append('<li>'+message+'</li>', '<li>'+e.message+'</li>' );
                console.log(e.message);
            });
        }
    }

    qz.security.setCertificatePromise(function(resolve, reject) {

        resolve("-----BEGIN CERTIFICATE-----\n"+
        "MIIElTCCA32gAwIBAgIJAIw8Z2Np2+UFMA0GCSqGSIb3DQEBBQUAMIGNMQswCQYD\n"+
        "VQQGEwJDQTELMAkGA1UECBMCT04xEDAOBgNVBAcTB1RPUk9OVE8xETAPBgNVBAoT\n"+
        "CFNURVJJTE9HMQwwCgYDVQQLEwNERVYxGDAWBgNVBAMUDyouMTU5Ljg5LjExNy4y\n"+
        "NjEkMCIGCSqGSIb3DQEJARYVa2F6aWFobWVkOTFAZ21haWwuY29tMB4XDTE4MDQy\n"+
        "MzIyMTcxMFoXDTQ5MTAxNjIyMTcxMFowgY0xCzAJBgNVBAYTAkNBMQswCQYDVQQI\n"+
        "EwJPTjEQMA4GA1UEBxMHVE9ST05UTzERMA8GA1UEChMIU1RFUklMT0cxDDAKBgNV\n"+
        "BAsTA0RFVjEYMBYGA1UEAxQPKi4xNTkuODkuMTE3LjI2MSQwIgYJKoZIhvcNAQkB\n"+
        "FhVrYXppYWhtZWQ5MUBnbWFpbC5jb20wggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAw\n"+
        "ggEKAoIBAQCwrltXHZSpTG6wkYR/FxO5jtepdyIfzNdk/Y9ofGKMkYLRrrT8nXyl\n"+
        "CTrTN1d8Gq3WB1cOROrbCtFghMfbmLNS3xBc7+hZieddUBZYEG1d5/h4RCeqQXbg\n"+
        "8Ag1pRojvqUV/06qmak4Bxk4uS3IOwFQTiWbil3p0onQmEGL4DClZYN8a/mxfDEj\n"+
        "LaWcuFlUw/xS6E7QjGQoqegtIfmseMBuEtNzs7axWGSM/dkmIXU6JUuAuR0TmjNE\n"+
        "ljkNe2HeYvT8443UJFyJCuQXOREVITbQI24F7i0V/uhHf+CeVKgaKnOxm76LSlqC\n"+
        "fGxldYZIoYA4oqBpc2XP9qcFZ1KJCNVRAgMBAAGjgfUwgfIwHQYDVR0OBBYEFDku\n"+
        "fZtcwEGl5iBgR47Ar/o1FI9RMIHCBgNVHSMEgbowgbeAFDkufZtcwEGl5iBgR47A\n"+
        "r/o1FI9RoYGTpIGQMIGNMQswCQYDVQQGEwJDQTELMAkGA1UECBMCT04xEDAOBgNV\n"+
        "BAcTB1RPUk9OVE8xETAPBgNVBAoTCFNURVJJTE9HMQwwCgYDVQQLEwNERVYxGDAW\n"+
        "BgNVBAMUDyouMTU5Ljg5LjExNy4yNjEkMCIGCSqGSIb3DQEJARYVa2F6aWFobWVk\n"+
        "OTFAZ21haWwuY29tggkAjDxnY2nb5QUwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0B\n"+
        "AQUFAAOCAQEARk/TqyrHUhft+mfvj/2KeTqUC+8PWu16hQWCzrEAmxGBEj36y41k\n"+
        "cXhNRbsSw6KQmN+eEWT/pT9mM+V/ka/BXVXsyK8+9oWGGpJhlvUHFHfNWjYi7O86\n"+
        "dKbLR92G4Pp1qagmtyjHhs+U5qwSnFuib95RbEvEteP055jJpzaHXgzynZ6bbdVk\n"+
        "U34462F5qr31iqj/KG7BpMKxTjVpHZAyEbwNuYdvJPJ6p7JXjHRDl89u10UO1VZE\n"+
        "pl+x7Vfblb7SGVB0vjETew+ECQZr++oc9y6ip7dL1lClmcAKE69RclLghSz/ufSg\n"+
        "0SDkknzYI/rJnZUY9WBYix5SMmUdtwHzyw==\n"+
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