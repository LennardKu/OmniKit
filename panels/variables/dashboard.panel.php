<div class="table-header py-3 block"> 
  <h2 class="text-4xl font-extrabold dark-disable:text-white">Standaard variables</h2>            
  <button data-omnikit-btn data-function="variables.create()" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark-disable:bg-blue-600 dark-disable:hover:bg-blue-700 dark-disable:focus:ring-blue-800">
    Creëen
    <svg aria-hidden="true" class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
  </button>
</div>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark-disable:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark-disable:bg-gray-700 dark-disable:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    #
                </th>
                <th scope="col" class="px-6 py-3">
                    Naam
                </th>
                <th scope="col" class="px-6 py-3">
                    Slug
                </th>
                <th scope="col" class="px-6 py-3">
                    Content
                </th>
                <th scope="col" class="px-6 py-3">
                    
                </th>
            </tr>
        </thead>
        <tbody data-omnikit-tables="globalVariables"></tbody>
      </table>
    </div>


<script defer>
  window.addEventListener('load', function () {
    OmniKit.variables.fillTable('globalVariables',{page: 'ajax/variables/get.php?all=true'})
  });
</script>