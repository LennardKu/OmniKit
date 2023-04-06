"use strict"


const OmniKit = {
  pluginUrl: null,

  config: async function () {

    this.pluginUrl = this.pluginUrl.split('src')[0]

    OmniKit.toast.config();
    // OmniKit.updateTables();

    // console.log(await OmniKit.getData(`${OmniKit.pluginUrl}/ajax/variables/get.php`));

    // Init
    OmniKit.init();
  },

  init: function () {


    // Load Content
    OmniKit.buttons();
  },


  buttons: function () {
    const buttons = document.querySelectorAll('[data-omnikit-btn]');
    buttons.forEach(button => {
      if(button.classList.contains('function-added')){ return; }
      button.classList.add('function-added');
      const functionName = button.getAttribute('data-function'),
      constructorName = (button.getAttribute('data-constructor') ? `${button.getAttribute('data-function')}.` : 'OmniKit.');
      button.addEventListener('click', function () {
        OmniKit.executeFunction(functionName,constructorName);
      });

    });
  },

  executeFunction: function( functionName = '',constructorName = 'OmniKit.') {
    eval(`${constructorName}${functionName}`);
  },

  submitForm: function (uuid,formUid = '') {

    document.querySelectorAll(`[data-btn="${uuid}"]`).forEach(element => {
      if(element.getAttribute('data-loading')){ return; }
      element.setAttribute('data-loading',true);
      element.setAttribute('data-default-text', element.innerHTML);
      element.innerHTML = `<svg aria-hidden="true" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                      <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                      </svg>
                      Laden...`;
      let form = document.querySelectorAll(`[data-form="${formUid}"]`)[0],
      url = form.getAttribute('action');
      OmniKit.submit({url: url,form: form}).then( data => {

        if(data.error){
          OmniKit.toast.create({type: 'danger', title: (data.title ?? 'Er ging iets mis'),content: (data.content ?? ''), autoClose: true });
        }

        if(data.success){
          OmniKit.toast.create({type: 'success', title: (data.title ?? 'Success'),content: (data.content ?? ''), autoClose: true });
          form.reset();
        }

        element.innerHTML = element.getAttribute('data-default-text');
        element.removeAttribute('data-loading');

      }).catch(error => { console.log(`${error}`); });
    });
  },

  submit: async function(data = {}) {
    return new Promise(function(resolve, reject) {
        
      const xhr = new XMLHttpRequest();
      xhr.open('POST', `${data.url || ''}`,true);  
      let formData = new FormData(data.form);
      xhr.addEventListener('load', async function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          var jsonResponse = JSON.parse(xhr.responseText);

          setTimeout(() => {
            resolve(jsonResponse);  
          }, 250);
          
        }
      });
      
      xhr.send(formData);
    });
  },

  getData: async function (url = ''){
    return await fetch(url)
    .then(response => response.json())
    .then(data => {
      return data;
    })
    .catch(error => {
      console.error('Error fetching project data: ', error);
    });
  },

  updateTables: function () {
    var tables = document.querySelectorAll('[data-omnikit-tables]');
  
    // Loop through each table
    for (var i = 0; i < tables.length; i++) {
      var table = tables[i];
      // Get the data for the table
      var pageName = table.getAttribute('data-omnikit-tables');
      OmniKit.getData(`${OmniKit.pluginUrl}/${pageName}/`)
        .then(data => {
          var numRows = table.rows.length;
          var numCols = table.rows[0].cells.length;
  
          for (var j = 0; j < numRows; j++) {
            var rowData = data[j];
            for (var k = 0; k < numCols; k++) {
              var cellData = rowData[k];
              table.rows[j].cells[k].textContent = cellData;
            }
          }
        })
        .catch(error => console.log(error));
    }
  },

  loadPage: async function(data = {}) {
    return new Promise(function(resolve, reject) {
      
      const container = (data.wrapper || document.querySelectorAll('main')[0]);
  
      const xhr = new XMLHttpRequest();
      xhr.open('GET', `${data.url || ''}`);  
      xhr.responseType = 'document';

      xhr.addEventListener('load', async function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          const newDoc = xhr.response;
          container.innerHTML = newDoc.documentElement.innerHTML;

          if(data.newUrl){
            history.pushState({}, '', `${data.newUrl}`);
          }
          setTimeout(() => {
            resolve({success:true});  
          }, 250);
          
        }
      });
      
      xhr.send();
    });
  },

  // Global variables
  variables: {
    
    single: function (uuid = '') {

      OmniKit.toast.create({title: 'Change',content: 'lorem ipsum',autoClose: true});
      
    },  

    create: function () {
      let uid =  OmniKit.createUuid();
      let formUid = OmniKit.createUuid();
      let body =  `<form data-form="${formUid}" action="${OmniKit.pluginUrl}/ajax/variables/create.php?submit=true" >
                      <div class="mb-6">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark-disable:text-white">Naam</label>
                        <input type="text" id="name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark-disable:bg-gray-700 dark-disable:border-gray-600 dark-disable:placeholder-gray-400 dark-disable:text-white dark-disable:focus:ring-blue-500 dark-disable:focus:border-blue-500 dark-disable:shadow-sm-light" placeholder="Naam" required>
                      </div>
                      <div class="mb-6">
                        <label for="slug" class="block mb-2 text-sm font-medium text-gray-900 dark-disable:text-white">Slug</label>
                        <input type="text" id="slug" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark-disable:bg-gray-700 dark-disable:border-gray-600 dark-disable:placeholder-gray-400 dark-disable:text-white dark-disable:focus:ring-blue-500 dark-disable:focus:border-blue-500 dark-disable:shadow-sm-light" required>
                      </div>
                      <div class="mb-6">
                        <label for="Content" class="block mb-2 text-sm font-medium text-gray-900 dark-disable:text-white">Content</label>
                        <input type="text" id="Content" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark-disable:bg-gray-700 dark-disable:border-gray-600 dark-disable:placeholder-gray-400 dark-disable:text-white dark-disable:focus:ring-blue-500 dark-disable:focus:border-blue-500 dark-disable:shadow-sm-light" required>
                      </div>
                      
                    </form>`;
      OmniKit.modal.create({title: 'Variable aanmaken',body: body,primaryBtn: `<button data-btn="${uid}" data-omnikit-btn data-function="submitForm('${uid}', '${formUid}')" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark-disable:bg-blue-600 dark-disable:hover:bg-blue-700 dark-disable:focus:ring-blue-800">Aanmaken</button>    `});
    },

    fillTable: async function (tableName = '',data = {}) {
      const table = document.querySelectorAll(`[data-omnikit-tables=${tableName}]`)[0];
      const offset = (table.getAttribute('data-offset') ?? 0);

      OmniKit.getData(`${OmniKit.pluginUrl}${data.page}/?offset=${offset}&limit=25`)
        .then(data => {
          for (let key in data){
            let variable = data[key];
            OmniKit.variables.placeItem(tableName, variable, key);
          }
          OmniKit.buttons();
          table.setAttribute('data-offset', Number(offset) + 25);
        }).catch(error => console.log(error));
    },  

    placeItem: function (tableName, variable, key) {
      const table = document.querySelectorAll(`[data-omnikit-tables=${tableName}]`)[0];
      const offset = (table.getAttribute('data-offset') ?? 0);
      const item = document.createElement('tr');

      item.innerHTML = `<tr class="bg-white border-b dark-disable:bg-gray-900 dark-disable:border-gray-700">
                          <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark-disable:text-white">
                              <strong class="black">${(Number(key) + 1 + Number(offset))}</strong>
                          </th>
                          <td class="px-6 py-4">
                            ${variable.name}
                          </td>
                          <td class="px-6 py-4">
                              Laptop
                          </td>
                          <td class="px-6 py-4">
                              $2999
                          </td>
                          <td class="px-6 py-4">
                              <a href="#" class="font-medium text-blue-600 dark-disable:text-blue-500 hover:underline" data-omnikit-btn data-function="variables.single('${variable.id}')">Wijzigen</a>
                          </td>
                      </tr>`;
      table.append(item);
    },


  },

  // Modal
  modal: {

    create: function (data = {}) {
      const modal = document.createElement('div'),
      uuid = OmniKit.createUuid();

      modal.setAttribute('data-omnikit-modal',(data.modalUuid ?? uuid));

      if(data.type == 'default' || data.type == undefined){
        modal.innerHTML = `<div class="relative w-full h-full max-w-2xl md:h-auto">
                            <div class="relative bg-white rounded-lg shadow dark-disable:bg-gray-700">
                                <!-- Modal header -->
                                <div class="flex items-start justify-between p-4 border-b rounded-t dark-disable:border-gray-600">
                                    <h3 class="text-xl font-semibold text-gray-900 dark-disable:text-white">
                                      ${data.title ?? ''}
                                    </h3>
                                    <button data-close="${uuid}" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark-disable:hover:bg-gray-600 dark-disable:hover:text-white" >
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <div class="p-6 space-y-6">${data.body ?? ''}</div>
                                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark-disable:border-gray-600 justify-between	">
                                  <button data-close="${uuid}"  type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark-disable:bg-gray-700 dark-disable:text-gray-300 dark-disable:border-gray-500 dark-disable:hover:text-white dark-disable:hover:bg-gray-600 dark-disable:focus:ring-gray-600">Sluiten</button>
                                  ${data.primaryBtn ?? ''}
                                </div>
                            </div>
                        </div>`;
      }

      if(data.type == 'confirm'){
        modal.innerHTML = `<div id="popup-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
                            <div class="relative w-full h-full max-w-md md:h-auto">
                                <div class="relative bg-white rounded-lg shadow dark-disable:bg-gray-700">
                                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark-disable:hover:bg-gray-800 dark-disable:hover:text-white" data-modal-hide="popup-modal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                    <div class="p-6 text-center">
                                        <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark-disable:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark-disable:text-gray-400">Are you sure you want to delete this product?</h3>
                                        <button data-modal-hide="popup-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark-disable:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                            Yes, I'm sure
                                        </button>
                                        <button data-modal-hide="popup-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark-disable:bg-gray-700 dark-disable:text-gray-300 dark-disable:border-gray-500 dark-disable:hover:text-white dark-disable:hover:bg-gray-600 dark-disable:focus:ring-gray-600">No, cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
      }

      document.querySelectorAll('body')[0].append(modal);
      OmniKit.buttons();
      let closeButtons = document.querySelectorAll('[data-close]');
      closeButtons.forEach(element => {
        element.addEventListener('click', function () {
          document.querySelectorAll(`[data-omnikit-modal="${element.getAttribute('data-close')}"]`)[0].remove();
        });
      });
    },

    close: function (uuid = null) {
      document.querySelectorAll(`[data-omnikit-modal="${uuid}"]`).remove();
    },

  },

  // Toast
  toast: {
    wrapper: null,

    config: function (){
      const wrapper = document.createElement('div');
      wrapper.classList.add('omnikit-toast-wrapper');
      document.body.append(wrapper);
      this.wrapper = wrapper;
    },

    create: function (data = {}) {
      const toastElement = document.createElement('div'),
      uuid = OmniKit.createUuid();
      toastElement.classList.add('active','omnikit-toast-element');
      let close = '',icon = '',color = '';
      let type = (data.type ?? 'success');
      
      if(data.autoClose == undefined){
        close = `<div class="omnikit-toast-close " data-close="${uuid}"> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 15.642 15.642"> <path fill-rule="evenodd" d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z"></path> </svg> </div>`;
      }

      // Icon
      color = 'toast--green';
      icon = `<svg version="1.1" class="omnikit-toast-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"> <g><g><path d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0    c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7    C514.5,101.703,514.499,85.494,504.502,75.496z"></path></g></g> </svg>`;
      
      if(type == 'info'){
        color = 'toast--blue';
        icon = `<svg version="1.1" class="toast__svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" style="enable-background:new 0 0 32 32;" xml:space="preserve"><g><g id="info"><g><path  d="M10,16c1.105,0,2,0.895,2,2v8c0,1.105-0.895,2-2,2H8v4h16v-4h-1.992c-1.102,0-2-0.895-2-2L20,12H8     v4H10z"></path><circle  cx="16" cy="4" r="4"></circle></g></g></g></svg>`;
      }
      
      if(type == 'danger'){
        color = 'toast--yellow';
        icon = `<svg version="1.1" class="toast__svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 301.691 301.691" style="enable-background:new 0 0 301.691 301.691;" xml:space="preserve"><g><polygon points="119.151,0 129.6,218.406 172.06,218.406 182.54,0  "></polygon><rect x="130.563" y="261.168" width="40.525" height="40.523"></rect></g> </svg>`
      }

      toastElement.innerHTML = `<div class="toast ${color}"><div class="omnikit-toast-icon">${icon}</div><div class="omnikit-toast-content"><p class="omnikit-toast-type">${data.title ?? ''}</p> <p class="omnikit-toast-message">${data.content ?? ''}</p> </div>${close} </div>`;

      this.wrapper.append(toastElement);

      // Auto close
      if(data.autoClose !== undefined){
        setTimeout(() => {
          OmniKit.toast.close(toastElement);
        }, 2000);
      }

      const closeBtn = toastElement.querySelectorAll('[data-close]');
      closeBtn.forEach(btn => {
        btn.addEventListener('click', function () {
          OmniKit.toast.close(toastElement);
        });
      });
    },

    close: function (element) {
      element.classList.remove('active');
      setTimeout(() => {
        element.remove();
      }, 650);
    },
  },

  createUuid: function () {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
      (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
  },

};                          

OmniKit.pluginUrl = document.currentScript.getAttribute('src');
window.addEventListener('load', function () {
  OmniKit.config();
});



