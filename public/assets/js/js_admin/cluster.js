if (typeof baseUrl === 'undefined') {
    // var baseUrl = 'http://192.168.1.5:8010/api';
    var baseUrl = "http://127.0.0.1:8010/api";
}



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


async function loadClusters(IdSelect) {
    try {
        const response = await axios.get(`${baseUrl}/get-cluster`,{params:{token:auth}});
        const clusters = response.data.data;
        const selectElement = document.getElementById(IdSelect);
        clusters.forEach(cluster => {
            const option = document.createElement('option');
            option.value = cluster.id;
            option.textContent = cluster.cluster;
            option.id = cluster.cluster;
            selectElement.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading clusters:', error);
    }
}



// mengambil data

document.addEventListener("DOMContentLoaded", () => {
    auth = document.getElementById('auth_token').value; 
    // Replace with your actual base URL
    const tableBody = document.getElementById("table-body");
    const searchInput = document.getElementById("default-search");
    const perPageSelect = document.getElementById("countries");
    loadClusters('tema-cluster');

    let currentPage = 1;

    // Function to fetch data from API
    const fetchData = async (page = 1, search = "", perPage = 10) => {
        try {
            const response = await axios.get(`${baseUrl}/get-cluster-paginate`, {
                params: {
                    token:auth,
                    page: page,
                    search: search,
                    perPage: perPage,

                },
            });
            const data = response.data;
            console.log(data);
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
                    ${item.cluster}
                </td>
                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                    ${item.created_at}
                </td>
                <td class="px-6 py-4">
                    ${item.updated_at}
                </td>
                <td class="px-6 py-4">
                 
                    <button data-id="${item.id
                }" data-modal-target="CrudModalCluster" data-modal-toggle="CrudModalCluster" class="px-2 text-xs my-1 w-full py-3 bg-yellow-400 text-white font-bold text-center hover:bg-yellow-700 rounded">
                        <div class="flex justify-center">
                            <p class="text-gray-200"><i class="fa-regular fa-pen-to-square text-lg text-white"></i></p>
                            <p class="mx-1 mt-2">Edit</p>
                        </div>
                    </button>
                    <button data-id="${item.id
                }" data-modal-target="popup-modal-cluster" data-modal-toggle="popup-modal-cluster" class="px-2 text-xs my-1 block w-full py-3 bg-red-400 text-white font-bold text-center hover:bg-red-700 rounded">
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

    const showEditModalCluster = async (id) => {
        try {
            const response = await axios.post(
                `${baseUrl}/get-cluster-by-id`,
                { token:auth, id }
            );
            const data = response.data.data;
            console.log(data);

            //get value barisnya

            document.getElementById("id").value = data.id;
            document.getElementById("clusterEdit").value =
                data.cluster;
            //get untuk value modal
            const crudModal = document.getElementById("CrudModalCluster");
            crudModal.classList.remove("hidden");
        } catch (error) {
            console.error("Failed to load data: ", error);
        }
    };

    //jika tombol di klik
    document.body.addEventListener("click", async (event) => {
        if (event.target.closest("[data-modal-target='CrudModalCluster']")) {
            const id = event.target
                .closest("[data-id]")
                .getAttribute("data-id");
            await showEditModalCluster(id);
        }
    });

    //jika tombol delete di klik
    document.body.addEventListener("click", async (event) => {
        if (event.target.closest("[data-modal-target='popup-modal-cluster']")) {
            console.log('hello');
            const id = event.target
                .closest("[data-id]")
                .getAttribute("data-id");

            document.getElementById("id-delete").value = id;
            document.getElementById("popup-modal-cluster").classList.remove("hidden");
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






//////////////////////// TEMA ZONE ///////////////////////////////////////


////////////////////////////          /////////////////////////////

    ////////////// ///////////////////, //////////////////////////











document.addEventListener("DOMContentLoaded", () => {
    // Replace with your actual base URL
    const tableBody = document.getElementById("table-body-tema");
    const searchInput = document.getElementById("default-search-tema");
    const perPageSelect = document.getElementById("countries-tema");


    let currentPage = 1;

    // Function to fetch data from API
    const fetchData = async (page = 1, search = "", perPage = 10) => {
        try {
            const response = await axios.get(`${baseUrl}/get-tema-paginate`, {
                params: {
                    token:auth,
                    page: page,
                    search: search,
                    perPage: perPage,

                },
            });
            const data = response.data;
            console.log(data);
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
                    ${item.nama}
                </td>
                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                    ${item.cluster.cluster}
                </td>
                   <td class="px-6 py-4">
                    ${item.created_at}
                </td>
                <td class="px-6 py-4">
                    ${item.updated_at}
                </td>
                <td class="px-6 py-4">
                 
                    <button data-id="${item.id
                }" data-modal-target="EditTema" data-modal-toggle="EditTema" class="px-2 text-xs my-1 w-full py-3 bg-yellow-400 text-white font-bold text-center hover:bg-yellow-700 rounded">
                        <div class="flex justify-center">
                            <p class="text-gray-200"><i class="fa-regular fa-pen-to-square text-lg text-white"></i></p>
                            <p class="mx-1 mt-2">Edit</p>
                        </div>
                    </button>
                    <button data-id="${item.id
                }" data-modal-target="popup-modal-tema" data-modal-toggle="popup-modal-tema" class="px-2 text-xs my-1 block w-full py-3 bg-red-400 text-white font-bold text-center hover:bg-red-700 rounded">
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

    const showEditModalTema = async (id) => {
        try {
            const response = await axios.post(
                `${baseUrl}/get-tema-id-first`,
                {token:auth, id }
            );
            const data = response.data.data;
            console.log(data);

            //get value barisnya

            document.getElementById("idTema").value = data.id;
            document.getElementById("namaTema").value =
                data.nama;
            document.getElementById("first-choose").textContent =
                data.cluster.cluster;   
            document.getElementById("first-choose").value =
                data.cluster.id;   

            loadClusters('tema-cluster-edit');
            //get untuk value modal
            const crudModal = document.getElementById("EditTema");
            crudModal.classList.remove("hidden");
        } catch (error) {
            console.error("Failed to load data: ", error);
        }
    };

    //jika tombol di klik
    document.body.addEventListener("click", async (event) => {
        if (event.target.closest("[data-modal-target='EditTema']")) {
            const id = event.target
                .closest("[data-id]")
                .getAttribute("data-id");
            await showEditModalTema(id);
        }
    });

    //jika tombol delete di klik
    document.body.addEventListener("click", async (event) => {
        if (event.target.closest("[data-modal-target='popup-modal-tema']")) {
     

            const id = event.target
                .closest("[data-id]")
                .getAttribute("data-id");
           
            document.getElementById("id-delete-tema").value = id;
            document.getElementById("popup-modal-tema").classList.remove("hidden");
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
