if (typeof baseUrl === 'undefined') {
    // var baseUrl = 'http://192.168.1.5:8010/api';
    var baseUrl = "http://127.0.0.1:8010/api";
}

const idTema = localStorage.getItem("temaId");



const rencanaAksiUrl = "{{ route('rencana-aksi')}}";
window.onload = function () {
    const alert = document.getElementById("alert");

    // Hentikan animasi bounce setelah selesai
    if (alert) {
        alert.addEventListener("animationend", function () {
            alert.classList.remove("animate-bounce");
        });

        // Menghilangkan alert setelah 2 detik
        setTimeout(function () {
            alert.classList.add(
                "opacity-0",
                "transition-opacity",
                "duration-500",
                "ease-out"
            );
            setTimeout(function () {
                alert.remove(); // Hapus elemen setelah transisi selesai
            }, 500); // Waktu transisi (500ms)
        }, 2000); // 2000ms = 2 detik
    }
};

// mengambil data
function initializeModals() {
    const modals = document.querySelectorAll('[data-modal-toggle]');
    modals.forEach(modalToggle => {
        const modalId = modalToggle.getAttribute('data-modal-target');
        const modal = document.getElementById(modalId);
        console.log(modal);
        if (modal) {
            new Modal(modal);
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    auth = document.getElementById('auth_token').value; 
    // Replace with your actual base URL
    const tableBody = document.getElementById("table-body");
    const searchInput = document.getElementById("default-search");
    const perPageSelect = document.getElementById("countries");
    const TemaTitle = document.getElementById("tema");
    const idInputErbType = document.getElementById("erb_type_id");


    let currentPage = 1;
    const Tema = async (id, element) => {

        const response = await axios.get(`${baseUrl}/get-tema-id`, {
            params: {token:auth, id: id }
        });
        element.textContent = response.data.data[0].nama;
    }

    Tema(idTema, TemaTitle);
    //input create change
    idInputErbType.value = idTema;



    // Function to fetch data from API
    const fetchData = async (page = 1, search = "", perPage = 10) => {
        try {
            const response = await axios.get(`${baseUrl}/get-permasalahan`, {
                params: {
                    token:auth,
                    erb_type_id: idTema,
                    page: page,
                    search: search,
                    perPage: perPage,

                },
            });
            const data = response.data;
            updateTable(data.data);
            updatePagination(data);
        } catch (error) {
            console.error("Request failed:", error);
        }
    };

    // Function to update table with data
    const updateTable = (data) => {
        tableBody.innerHTML = ""; // Clear existing rows
        data.forEach((item, index) => {
            const row = document.createElement("tr");
            row.classList.add(
                "bg-white",
                "border-b",
                "dark:bg-gray-800",
                "dark:border-gray-700"
            );

            row.innerHTML = `
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">${index + 1
                }</th>
                 <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                    ${item.unique_namespace}
                </td>
                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                    ${item.permasalahan}
                </td>
                <td class="px-6 py-4">
                    ${item.sasaran}
                </td>
                <td class="px-6 py-4">
                    ${item.indikator}
                </td>
                <td class="px-6 py-4">
                    ${item.target}
                </td>
                <td class="px-4 py-2">
                  
                    <button data-id="${item.id
                }"  class=" rencana-aksi-button px-2 text-xs my-1 w-full py-2 bg-blue-400 text-white font-bold text-center hover:bg-blue-700 rounded">
                            <div class="flex justify-center">
                                <p class="text-gray-200"><i class="fa-regular fa-lightbulb text-lg text-white"></i></p>
                                <p class="mx-1 mt-2">Rencana Aksi</p>
                            </div>
                    </button>
                 
                    <button data-id="${item.id
                }" data-modal-target="CrudModal" data-modal-toggle="CrudModal" class="px-2 text-xs my-1 w-full py-2 bg-yellow-400 text-white font-bold text-center hover:bg-yellow-700 rounded">
                        <div class="flex justify-center">
                            <p class="text-gray-200"><i class="fa-regular fa-pen-to-square text-lg text-white"></i></p>
                            <p class="mx-1 mt-2">Edit</p>
                        </div>
                    </button>
                    <button data-id="${item.id
                }" data-modal-target="popup-modal2" data-modal-toggle="popup-modal2" class="px-2 text-xs my-1 block w-full py-2 bg-red-400 text-white font-bold text-center hover:bg-red-700 rounded">
                        <div class="flex justify-center">
                            <p class="text-gray-200"><i class="fa-regular fa-trash-can text-white text-lg"></i></p>
                            <p class="mx-1 mt-2">Delete</p>
                        </div>
                    </button>
                </td>
            `;

            tableBody.appendChild(row);
        });
        document.querySelectorAll(".rencana-aksi-button").forEach((button) => {
            button.addEventListener("click", (e) => {
                e.preventDefault();
                const idPermasalahan = button.getAttribute("data-id");
                localStorage.setItem("id_permasalahan", idPermasalahan);
                window.location.href = "/rencana-aksi";
            });
        });
        tableBody.addEventListener('click', (event) => {
            const target = event.target.closest('[data-modal-toggle]');
            if (target) {
              const modalId = target.getAttribute('data-modal-target');
              const modal = document.getElementById(modalId);
              if (modal) {
                const modalInstance = new Modal(modal);
                modalInstance.toggle();
              }
            }
          });
    };
    initializeModals();
    

    const updatePagination = (data) => {
        const pagination = document.getElementById("pagination");
        pagination.innerHTML = ""; // Clear existing pagination

        // Previous button
        const prevLi = document.createElement("li");
        prevLi.innerHTML = `
            <a href="#" class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                <span class="sr-only">Previous</span>
                <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                </svg>
            </a>
        `;
        prevLi.addEventListener("click", (e) => {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                fetchData(currentPage, searchInput.value, perPageSelect.value);
            }
        });
        pagination.appendChild(prevLi);

        // Page number buttons
        for (let i = 1; i <= data.last_page; i++) {
            const li = document.createElement("li");
            li.innerHTML = `
                <a href="#" class="flex items-center justify-center px-4 h-10 leading-tight ${i === currentPage
                    ? "text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                    : "text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                }">${i}</a>
            `;
            li.addEventListener("click", (e) => {
                e.preventDefault();
                currentPage = i;
                fetchData(currentPage, searchInput.value, perPageSelect.value);
            });
            pagination.appendChild(li);
        }

        // Next button
        const nextLi = document.createElement("li");
        nextLi.innerHTML = `
            <a href="#" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                <span class="sr-only">Next</span>
                <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
            </a>
        `;
        nextLi.addEventListener("click", (e) => {
            e.preventDefault();
            if (currentPage < data.last_page) {
                currentPage++;
                fetchData(currentPage, searchInput.value, perPageSelect.value);
            }
        });
        pagination.appendChild(nextLi);
    };

    const showEditModal = async (id) => {
        try {
            const response = await axios.post(
                `${baseUrl}/get-permasalahan-id`,
                { token:auth, id }
            );
            const data = response.data.data;

            //get value barisnya

            document.getElementById("id").value = data.id;
            document.getElementById("permasalahan-edit").value =
                data.permasalahan;
            document.getElementById("namespaceEdit").value = data.unique_namespace
            document.getElementById("sasaran-edit").value = data.sasaran;
            document.getElementById("indikator-edit").value = data.indikator;
            document.getElementById("target-edit").value = data.target;

            //get untuk value modal
            const crudModal = document.getElementById("CrudModal");
            crudModal.classList.remove("hidden");
        } catch (error) {
            console.error("Failed to load data: ", error);
        }
    };

    //jika tombol di klik
    document.body.addEventListener("click", async (event) => {
        if (event.target.closest("[data-modal-target='CrudModal']")) {
            const id = event.target
                .closest("[data-id]")
                .getAttribute("data-id");
            await showEditModal(id);
        }
    });

    //jika tombol delete di klik
    document.body.addEventListener("click", async (event) => {
        if (event.target.closest("[data-modal-target='popup-modal2']")) {

            const id = event.target
                .closest("[data-id]")
                .getAttribute("data-id");

            document.getElementById("id-delete").value = id;
            document.getElementById("popup-modal2").classList.remove("hidden");
        }
    });

    fetchData();

    searchInput.addEventListener("input", () => {
        currentPage = 1;
        fetchData(currentPage, searchInput.value, perPageSelect.value);
    });

    perPageSelect.addEventListener("change", () => {
        currentPage = 1;
        fetchData(currentPage, searchInput.value, perPageSelect.value);
    });
});
