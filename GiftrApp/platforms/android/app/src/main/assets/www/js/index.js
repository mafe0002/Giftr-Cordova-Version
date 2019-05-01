var app = {
    KEY: 'mafe0002',
	url: "http://liu00415.edumedia.ca/giftr_api/api/",
	people,
    addPeople: document.getElementById('addPple'),
    cancel: document.getElementById('cancel'),
    save: document.getElementById('save'),
    addGift: document.getElementById('addGift'),
    saveGift: document.getElementById('saveGift'),
    fromGiftPage: document.getElementById('fromGiftPage'),
    fromAddGift: document.getElementById('fromAddGift'),
    peoplePage: document.getElementById('people'),
    addPeoplePage: document.getElementById('addPeople'),
    gift: document.getElementById('gift'),
    giftIdea: document.getElementById('addIdea'),
	fabAdd: document.getElementById('fabAdd'),
    generateGiftList: true,
    reloadGift: false,
    token: "",
	tokenSet: false,
    retainIdeas: false,
    monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],

    init: ()=>{
		  app.registerUser();
           
        //add listeners
	   app.addPeople.addEventListener('click', app.showForm),
	   app.fabAdd.addEventListener('click', app.fab),
       app.cancel.addEventListener('click', app.backToContacts),
       app.save.addEventListener('click', app.addContacts),
       app.fromAddGift.addEventListener('click', function() {
           app.generateGiftList = false;
           app.displayGiftList();
           app.generateGiftList = true;
       }),
       app.fromGiftPage.addEventListener('click', app.backToContacts),
       app.addGift.addEventListener('click', app.displayGiftIdeaPage),
       app.saveGift.addEventListener('click', app.addGiftIdea);
        
    },
	registerUser: ()=>{
		if(app.tokenSet){
			app.getData();
		}else{
		setTimeout(async()=>{
		console.log(device.cordova);
//		//Here we check to see if uuid exists 
		if(device.uuid){
            //If we are using a device with UUID
            let deviceID = device.uuid,
            parameter = "device_id=" + deviceID;
        
            let userRegister = await api.fetchAsync(`${app.url}users.php`, "POST", null, parameter);
            console.log('New Token:', userRegister.data.token);
            app.token = userRegister.data.token;
            app.getData();
			app.tokenSet = true;
        }else{
			app.tokenSet = false;
			console.log('already have token');
            //If we are using emulator, no UUID is available. We'll just use the hard coded token. 
            app.token = '1234567';
			app.getData();
        }
			
			}, 1000);
			
			}
		
	},
	getData: async ()=>{
		//fetch goes here
		let response = await api.fetchAsync(`${app.url}people.php`, "GET", `${app.token}`, null);
        console.log('People', response.data);
		app.people = response.data;
		
		 //update local storage
        app.updateLocal();
		app.updateContact();
		
		 //check if browser supports template
        if(app.supportsTemplate()){
            app.useFirstTemplate();
        }else{
            app.createIndividualCard();
        }
	
	},
    supportsTemplate: ()=>{ 
        return "content" in document.createElement('template');
    },
    createIndividualCard: ()=>{
//      create cards manually here
    },
    updateContact: ()=>{
        app.people = JSON.parse(localStorage.getItem('app.KEY'));
    },
    updateLocal: ()=>{
        let local = localStorage.setItem('app.KEY', JSON.stringify(app.people));
    },
    useFirstTemplate: ()=>{
           document.querySelector('#people .list-view').innerHTML = "";
		
            //clone the templates for the number of items in people array
        let contacts = app.people;
        
        let month = {"month": 0};
		let checkCurrent = {"birthdayPassed": false}
        
        let months = contacts.map(function(contact){
            let obj = Object.assign({}, contact, month, checkCurrent);
            let newDate = new Date(obj.dob);
            obj.month = newDate.getMonth() + 1;
			
			obj.birthdayPassed = moment(obj.dob, '____-MM-DD');
			
			if(moment().diff(obj.birthdayPassed, 'days') > 0){
			   obj.birthdayPassed = true;
			   }else{
				   obj.birthdayPassed = false;
			   }

            obj.dob = app.monthNames[newDate.getMonth()] + " " + (newDate.getDate() + 1);
            return obj;
			
        });
		
        let sorted = months.sort(function(a, b){return a.month - b.month});
		
		
//		
//		if(sorted.length == 0){
//			
//		}else{
          for(var i = 0; i < sorted.length; i++){
            let contact = sorted[i],
				template = document.getElementById('temp1').content.cloneNode(true);
			  
            template.querySelector('.contact').textContent = contact.person_name;
         
             template.querySelector('.list-item').setAttribute('id', contact.person_id);
			  
             template.getElementById('left').addEventListener('click', app.deleteContacts);
              
            template.getElementById('right').addEventListener('click', app.displayGiftList);
            template.querySelector('.contact').addEventListener('click', app.editContact);
            template.querySelector('.birthday').textContent = contact.dob;
             
			  if(contact.birthdayPassed){
				  template.querySelector('.birthday').classList.add('passed');
			  }
			  
               document.querySelector('#people .list-view').appendChild(template);
          } 
			
    },
	displayContacts: (present)=>{

		document.querySelector('.fab').classList.remove('opaq');
			app.changePage(present, app.peoplePage);
	
	},
	deleteContacts: async (ev)=>{
		
       let parent = (ev.currentTarget.parentNode).id,
		   node = (ev.currentTarget.parentNode),
		   ul = document.querySelector('#people .list-view'),
			msg = 'Contact Deleted';
		
		let responseDelete = await api.fetchAsync(`${app.url}people.php/${parent}`, "DELETE", `${app.token}`, null);
        console.log('DeletePerson', responseDelete);

        app.getData();
		

		setTimeout(()=>{
			let contact = app.people.filter(person => person.person_id != parent);
			app.people = contact;
			//console.log(app.people)
			
		}, 0);
		
   	    app.displayOverlay(msg, 800);
		ul.removeChild(node);
        
	},
	displayGiftList: (ev)=>{
        if(app.generateGiftList){
         //clear ul
         document.querySelector('#gift .list-view').innerHTML = "";
        
        let details = ev.currentTarget,
        id = (details.parentNode).id,
        iD;
         //setattribute on save button 
            let save = document.getElementById('saveGift');
            save.setAttribute('data-id', id);
             
            app.useSecondTemplate(id);
            
        }

       if((app.peoplePage).className === 'page active'){
		         app.changePage(app.peoplePage, app.gift);
            }else if((app.giftIdea).className === 'page active'){
                app.changePage(app.giftIdea, app.gift);
				document.querySelector('.fab').classList.remove('opaq');
            }else if((app.gift) === 'page active'){
				
			}
        
	},
	deleteGiftIdea: async (ev)=>{
		console.log('clicked');
           let child = ev.currentTarget.parentNode.getAttribute('data-id'),
           parent =(ev.currentTarget.parentNode).parentNode.getAttribute('data-id'),
           people, idea = [],
           ul = document.querySelector('#gift .list-view'),
		   msg = 'Gift Idea Deleted',
           node = (ev.currentTarget.parentNode).parentNode;

					let responseDelete = await api.fetchAsync(`${app.url}gifts.php/${child}`, "DELETE", `${app.token}`, null);
        console.log('DeleteGift', responseDelete);

        app.getData();
       
        ul.removeChild(node);
		
		app.displayOverlay(msg, 800);
	},
    useSecondTemplate: (num)=>{
		
		app.updateContact();
                //clone the templates for the number of items in gift array
        app.people.forEach(person=>{let personIdea = person.gifts;        
                                
        if(person.person_id == num){
			
			document.getElementById('giftOwner').textContent = person.person_name + "'s gifts.";
			
//			if(personIdea.length == 0){
//                template.querySelector('.giftIdea').textContent = "List is Empty.";
//				
//				    document.querySelector('#gift .list-view').appendChild(template);
//				
//			}else{
            for(var i = 0; i < personIdea.length; i++){
               
                let idea = personIdea[i];
                
				let template = document.getElementById('temp2').content.cloneNode(true);
				
                template.querySelector('.list-item').setAttribute('data-id', person.person_id);
                
                template.querySelector('div').setAttribute('data-id', idea.gift_id);
                
                template.getElementById('del').setAttribute('data-id', idea.gift_id);
                
                 template.getElementById('del').addEventListener('click', app.deleteGiftIdea);
                
                template.querySelector('.giftLocation').textContent = idea.gift_store;
                
                template.querySelector('.giftIdea').textContent = idea.gift_title;
                
                template.querySelector('a').textContent = idea.gift_url;
                
                template.querySelector('a').setAttribute('href', idea.gift_url);
				
               template.querySelector('.giftPrice').textContent = "$" + idea.gift_price;
                
                 document.querySelector('#gift .list-view').appendChild(template);
               
                                  }
				
                             }
                });
    },
	fab: ()=>{
	if((app.peoplePage).className === 'page active'){
		   document.querySelector('.fab').classList.add('opaq');
			  app.showForm();
		   }else if((app.gift).className === 'page active'){
			    document.querySelector('.fab').classList.add('opaq');
			   app.changePage(app.gift, app.giftIdea );
		   }
},
	addGiftIdea: async (ev)=>{
        //clear ul
         document.querySelector('#gift .list-view').innerHTML = "";
		
        let id = document.getElementById('saveGift').getAttribute('data-id'),
        giftInput = document.getElementById('idea').value,
        location = document.getElementById('location').value, 
        url = document.getElementById('online').value,
        price = document.getElementById('price').value,
		msg = 'Gift Idea Added';
		
		let online = document.getElementById('online'),
			validInput = (giftInput != ""),
			validPrice = (price != "") ,
			validPriceInt = !(isNaN(price)),
			//		    pattern = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/,
			pattern = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?(com|io|ca|org)/,
			num = 0;
		if(url != ""){
			num = url.search(pattern);
		}
        if((validInput && validPriceInt && validPrice && url == "")||(validInput && validPrice && validPriceInt && num != -1)){
		    let contact = app.people.filter(person => person.person_id == id);

			  let responsePost = await api.fetchAsync(`${app.url}gifts.php`, "POST", `${app.token}`, `gift_title=${giftInput}&person_id=${id}&gift_url=${url}&gift_price=${price}&gift_store=${location}`, null);
             console.log('PostGift', responsePost.data);
				let gift = responsePost.data,
				newGift = [{
					"gift_id": parseInt(gift.id),
				    "gift_title": gift.gift_title,
				    "gift_url":gift.gift_url,
					"gift_store":gift.gift_store,
				    "gift_price": gift.gift_price}]
			
			    let ideas = (contact[0].gifts).concat(newGift);
                   contact[0].gifts = ideas;
			
			app.people.forEach(person => {
          		if(person.person_id == id){
              		let index = app.people.indexOf(person);
              		app.people.splice(index, 1, contact[0])
          			}
             });
                     app.getData();
			         app.updateLocal();
		             app.updateContact();
		             app.useSecondTemplate(id);
					 app.changePage(app.giftIdea, app.gift);
			
			document.querySelector('.fab').classList.remove('opaq');
		    app.displayOverlay(msg, 800);
			
			document.getElementById('giftForm').reset();
       }else if((validInput && validPrice && validPriceInt && num == -1)){
			let message = "Please enter url in the format 'http://www.example.com'";
			app.displayOverlay(message, 3000);
		}else if((validInput && validPrice && !validPriceInt && num != -1)){
			let message = "Price must be a number";
			app.displayOverlay(message, 3000);
		}

	},
    displayGiftIdeaPage: ()=>{
        app.changePage(app.gift, app.giftIdea);
    },
	editContact: (ev)=>{
		document.querySelector('.fab').classList.add('opaq');
          //edit contacts
        app.retainIdeas = true;
        let target = ev.currentTarget,
            contactId = (target.parentNode).id,
            lists = document.querySelectorAll('#people .list-item');

		   app.people.forEach(person=>{
            if(person.person_id == contactId){
                
              document.getElementById('nm').value = person.person_name;
              document.getElementById('dob').value = person.dob
            }
        });
		
		//adding a class to make it distinct
        lists.forEach(list => {
            if(list.id == contactId){
				//console.log(list);
                list.classList.add('unique');
            }
        });
       
     
		app.changePage(app.peoplePage, app.addPeoplePage);
	},
    showForm: ()=>{
        app.changePage(app.peoplePage, app.addPeoplePage);
    },
	addContacts: async ()=>{ 
		
        //save inputs
        let name = document.getElementById('nm').value,
            date = document.getElementById('dob').value,
              idea,
			  msg = 'Contact Saved';
        if(!app.retainIdeas){
			
            if((name != "") && (date != "")){
				      let responsePost = await api.fetchAsync(`${app.url}people.php`, "POST", `${app.token}`, `person_name=${name}&dob=${date}&user_id=1`);
                 console.log('PostPerson', responsePost.data);
			
			}
        }else{
			
           let num = document.querySelector('#people .list-item.unique').getAttribute('id')
		
			people = app.people.filter(person => person.person_id == num);
            id = people[0].person_id;
            idea = people[0].gifts;
			let ID = people[0].user_id;
			
			let responsePut = await api.fetchAsync(`${app.url}people.php/${id}`, "PUT", `${app.token}`, {
        "person_name": `${name}`, "user_id": `${ID}`, "dob": `${date}`});
        console.log('PutPerson', responsePut.data);
			//console.log(app.people);
			
        }
        
             app.updateLocal();
		
	       //display new list of contacts
		    app.getData();
		   app.useFirstTemplate();
           app.displayOverlay(msg, 800);
		
            //change page
		        document.querySelector('.fab').classList.remove('opaq');
				app.changePage(app.addPeoplePage, app.peoplePage);

				document.getElementById('peopleForm').reset();
		
	},
	displayOverlay: (message, dur)=>{
		
		document.getElementById('info').innerText = message;
		document.querySelector('.overlay').style.display = "block";
		
		setTimeout(()=>{document.querySelector('.overlay').style.display = "none";
		document.getElementById('info').innerText = "";}, dur);
		
	},
    changePage: (currentpage, nextpage)=>{
      currentpage.classList.remove('active');
       nextpage.classList.add('active');
    },
    backToContacts: ()=>{
		
        if((app.addPeoplePage).className === 'page active'){
            document.getElementById('peopleForm').reset();
             app.displayContacts(app.addPeoplePage);
          }else if((app.giftIdea).className === 'page active'){
              app.displayContacts(app.giftIdea);
          }else{
              app.displayContacts(app.gift);
          }
    }
};
let deviceready = ('deviceready' in document) ? 'deviceready' : 'DOMContentLoaded';
//let deviceready = 'deviceready';
document.addEventListener(deviceready, app.init);