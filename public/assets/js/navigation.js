
if (typeof baseUrl === 'undefined') {
    // var baseUrl = 'http://192.168.1.5:8010/api';
    var baseUrl = "http://127.0.0.1:8010/api";
}
let auth;







document.addEventListener('DOMContentLoaded', function () {

    auth = document.getElementById('auth_token').value; 
    
    
    const reformasiBirokrasiButton = document.querySelector('[data-collapse-toggle="dropdown-example"]');
    const dropdownExample = document.getElementById('dropdown-example');

    reformasiBirokrasiButton.addEventListener('click', async function () {
        if (dropdownExample.classList.contains('hidden')) {
            try {
                const data = await fetchReformasiBirokrasiData();
                populateDropdown(dropdownExample, data);
                dropdownExample.classList.remove('hidden');
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        } else {
            dropdownExample.classList.add('hidden');
        }
    });

    document.getElementById('logout-button').addEventListener('click', function () {
        // Menghapus local storage
        localStorage.clear();
    });
});

async function fetchReformasiBirokrasiData() {
    const response = await axios.get(`${baseUrl}/get-cluster`,{params:{token:auth}});
    return response.data.data;
}

function populateDropdown(dropdownElement, data) {
    const submenu = document.createElement('ul');
    submenu.classList.add('py-2', 'space-y-2', 'ml-2');

    data.forEach(item => {
        const listItem = createDropdownItem(item);
        submenu.appendChild(listItem);
    });

    dropdownElement.innerHTML = '';
    dropdownElement.appendChild(submenu);
}

function createDropdownItem(item) {
    const listItem = document.createElement('li');
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('flex', 'items-center', 'w-full', 'p-2', 'text-base', 'text-white', 'transition', 'duration-75', 'rounded-lg', 'group', 'hover:bg-teal-900');
    button.setAttribute('aria-controls', `dropdown-${item.id}`);
    button.setAttribute('data-collapse-toggle', `dropdown-${item.id}`);
    button.innerHTML = `
    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">${item.cluster}</span>
    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
    </svg>
  `;

    const subDropdown = createSubDropdown(item.id);
    listItem.appendChild(button);
    listItem.appendChild(subDropdown);

    button.addEventListener('click', async function () {
        if (subDropdown.classList.contains('hidden')) {
            try {
                const subData = await fetchSubmenuData({ token:auth, id: item.id });
                console.log(subData);
                populateSubDropdown(subDropdown, subData);
                subDropdown.classList.remove('hidden');
            } catch (error) {
                console.error('Error fetching submenu data:', error);
            }
        } else {
            subDropdown.classList.add('hidden');
        }
    });

    return listItem;
}

async function fetchSubmenuData(params) {
    const response = await axios.get(`${baseUrl}/get-tema-id`, { params });
    return response.data.data;
}

function createSubDropdown(itemId) {
    const subDropdown = document.createElement('ul');
    subDropdown.id = `dropdown-${itemId}`;
    subDropdown.classList.add('hidden', 'py-2', 'space-y-2', 'ml-2');
    return subDropdown;
}

function populateSubDropdown(subDropdown, submenuItems) {
    subDropdown.innerHTML = '';
    console.log(submenuItems);

    submenuItems.forEach(subItem => {
        console.log(subItem)
        const subListItem = document.createElement('li');
        const subLink = document.createElement('a');
        subLink.href = '/e-rb-view';
        subLink.classList.add('flex', 'items-center', 'w-full', 'p-2', 'text-gray-900', 'transition', 'duration-75', 'rounded-lg', 'pl-11', 'group', 'hover:bg-gray-100', 'dark:text-white', 'dark:hover:bg-gray-700');
        subLink.textContent = subItem.nama;

        // Add click event listener to store the ID in local storage
        subLink.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default link behavior
            localStorage.setItem('temaId', subItem.id);
            console.log(`Stored temaId in local storage: ${subItem.id}`);
            window.location.href = '/e-rb-view'; // Navigate to the URL after storing the ID
        });

        subListItem.appendChild(subLink);
        subDropdown.appendChild(subListItem);
    });
}