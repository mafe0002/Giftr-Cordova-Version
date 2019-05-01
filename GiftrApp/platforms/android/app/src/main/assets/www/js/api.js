const api = {

    fetchAsync: async function (resource, method, token, parameters) {
        let options = {
            "method": method,
            "mode": 'cors'
        };
		options.headers = {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            };
        if (token) {
            options.headers = {
                "Giftr-Token": token,
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
                //"Content-type": 'application/json'
            };
        }

        if (parameters) {
            
            switch(method){
                case "POST":
                    options.body = parameters;
                    break;
                case "PUT":
                    options.body = JSON.stringify(parameters);
                    break;
                default:
                    console.log("Something not right with the method");
            }

        }

        let response = await fetch(resource, options);
        let data = await response.json();
        return data;
    }
}
