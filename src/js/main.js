"use strict"


const OmniKit = {
  pluginUrl: null,

  config: async function () {

    this.pluginUrl = this.pluginUrl.split('src')[0]

    OmniKit.toast.config();
    OmniKit.updateTables();

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

    },

    fillTable: async function (tableName = '',data = {}) {
      const table = document.querySelectorAll(`[data-omnikit-tables=${tableName}]`)[0];
      
      OmniKit.getData(`${OmniKit.pluginUrl}${data.page}/`)
        .then(data => {
          data.forEach(variable => {
            let item = document.createElement('tr');
            item.innerHTML = `<tr>
                                <td>1</td>
                                <td>${variable.name}</td>
                                <td>name</td>
                                <td>OmniKit</td>
                                <td class="pointer" data-omnikit-btn data-function="variables.single('${variable.id}')" >Wijzigen</td>
                              </tr>`;
              table.append(item);
            });
        }).catch(error => console.log(error));
    },  


  },

  // Modal
  modal: {

    create: function () {
      const modal = document.createElement('div'),
      uuid = OmniKit.createUuid();

      modal.setAttribute('data-omnikit-modal',uuid);

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



