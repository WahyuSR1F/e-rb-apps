if (typeof baseUrl === 'undefined') {
    // var baseUrl = 'http://192.168.1.5:8010/api';
    var baseUrl = "http://127.0.0.1:8010/api";
}

const idPermasalahan = localStorage.getItem("id_permasalahan");
console.log(idPermasalahan);

window.onload = function () {
    const alert = document.getElementById("alert");

    if (alert) {
        alert.addEventListener("animationend", function () {
            alert.classList.remove("animate-bounce");
        });

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
        }, 2000);
    }
};

// mengambil data

document.addEventListener("DOMContentLoaded", () => {
    auth = document.getElementById('auth_token').value; 
    const tableBody = document.getElementById("table-body");
    const searchInput = document.getElementById("default-search");
    const perPageSelect = document.getElementById("countries");
    const detailTitle = document.getElementById("title-problem");
    const titleSasaran = document.getElementById("title-sasaran");
    const idPermasalahanInput = document.getElementById("id-permasalahan");
    let currentPage = 1;


    // melakukan pemerikasaan data pada js apakah target penyelesaian sudah diisi atau tidak




    const fetchDataMasalah = async (id) => {

        try {
            const response = await axios.post(
                `${baseUrl}/get-permasalahan-id`,
                {
                    token:auth,
                    id: id,
                }
            );
            const data = response.data;
            return data;
        } catch (error) {
            console.error("Request failed:", error);
        }
    };

    const updateTitles = async () => {
        const data = await fetchDataMasalah(idPermasalahan);

        if (data) {
            detailTitle.textContent = data.data.permasalahan; // Ganti 'titleProblem' dengan key yang sesuai dari API
            titleSasaran.textContent = data.data.sasaran;
            idPermasalahanInput.value = data.data.id; // Ganti 'titleSasaran' dengan key yang sesuai dari API
        } else {
            console.error("Data tidak ditemukan atau terjadi kesalahan.");
        }
    };

    // Panggil fungsi untuk mengupdate title
    updateTitles();

    const fetchData = async (page = 1, search = "", perPage = 10) => {
        try {
            const response = await axios.get(`${baseUrl}/get-rencana-aksi`, {
                params: {
                    token:auth,
                    id: idPermasalahan,
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

    const updateTable = async (data) => {
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
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ${index + 1}
            </th>
            <td class="px-6 py-4">${item.unique_namespace}</td>
            <td class="px-6 py-4">${item.rencana_aksi}</td>
            <td class="px-6 py-4">${item.indikator}</td>
            <td class="px-6 py-4">${item.satuan}</td>
            <td class="px-6 py-4">${item.koordinator}</td>
            <td class="px-6 py-4">${item.pelaksana}</td>
            <td class="px-6 py-4">
                <div class="flex justify-center w-full mb-1 relative">
                    <button id="dropdownDefaultButton1_${index}" data-dropdown-toggle="dropdown1_${index}" class="text-white bg-teal-400 hover:bg-teal-700 font-bold rounded text-xs px-4 py-3 w-full my-1 text-center inline-flex items-center">
                        <i class="fa-solid fa-magnifying-glass text-lg mx-1"></i>
                        <span class="flex-1 text-center">Monev</span>
                        <svg class="w-2.5 h-2.5 ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                
                    <!-- Dropdown menu -->
                    <div id="dropdown1_${index}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 absolute left-0 right-0 top-full mt-1">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton1_${index}">
                            <li>
                                <a data-id="${item.id}" id="/realisasi-penyelesaian" class="target-penyelesaian flex items-center px-4 py-2 hover:bg-gray-100 font-bold text-center dark:hover:bg-gray-600 dark:hover:text-white">
                                    <i class="fa-solid fa-list-check mr-2"></i> Penyelesaian
                                </a>
                            </li>
                            <li>
                                <a data-id="${item.id}" id="/realisasi-anggaran" class="target-anggaran flex items-center px-4 py-2 hover:bg-gray-100 font-bold text-center dark:hover:bg-gray-600 dark:hover:text-white">
                                    <i class="fa-solid fa-dollar-sign mr-2"></i> Anggaran
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="flex justify-center w-full mb-1 relative">
                    <button id="dropdownTargetButton1_${index}" data-dropdown-toggle="dropdownTarget1_${index}" class="text-white bg-blue-400 hover:bg-blue-700 font-bold rounded text-xs px-4 py-3 w-full my-1 text-center inline-flex items-center">
                        <i class="fa-solid fa-magnifying-glass text-lg mx-1"></i>
                        <span class="flex-1 text-center">Target</span>
                        <svg class="w-2.5 h-2.5 ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                
                    <!-- Dropdown menu -->
                    <div id="dropdownTarget1_${index}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 absolute left-0 right-0 top-full mt-1">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownTargetButton1_${index}">
                            <li>
                                <a data-id="${item.id}" id="/target-penyelesaian" class="target-penyelesaian flex items-center px-4 py-2 hover:bg-gray-100 font-bold text-center dark:hover:bg-gray-600 dark:hover:text-white">
                                    <i class="fa-solid fa-list-check mr-2"></i> Penyelesaian
                                </a>
                            </li>
                            <li>
                                <a data-id="${item.id} " id="/target-anggaran" class="target-anggaran flex items-center px-4 py-2 hover:bg-gray-100 font-bold text-center dark:hover:bg-gray-600 dark:hover:text-white">
                                    <i class="fa-solid fa-dollar-sign mr-2"></i> Anggaran
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <button data-id="${item.id
                }" data-modal-target="crudModal1" data-modal-toggle="crudModal1" class="px-2 text-xs my-1 w-full py-3 bg-yellow-400 text-white font-bold text-center hover:bg-yellow-700 rounded">
                    <div class="flex justify-center">
                        <p class="text-gray-200"><i class="fa-regular fa-pen-to-square text-lg text-white"></i></p>
                        <p class="mx-1 mt-2">Edit</p>
                    </div>
                </button>
                <button data-id="${item.id
                }" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="px-2 text-xs my-1 block w-full py-3 bg-red-400 text-white font-bold text-center hover:bg-red-700 rounded">
                    <div class="flex justify-center">
                        <p class="text-gray-200"><i class="fa-regular fa-trash-can text-white text-lg"></i></p>
                        <p class="mx-1 mt-2">Delete</p>
                    </div>
                </button>
            </td>
        `;

            tableBody.appendChild(row);


            const Check = async (id) => {

                const responsePenyelesaian = await axios.post(
                    `${baseUrl}/get-target-penyelesaian-id`,
                    { token:auth, id }
                );
                const responseAnggaran = await axios.post(
                    `${baseUrl}/get-anggaran-id`,
                    { token:auth, id }
                );

                const penyelesaian = responsePenyelesaian.data;
                const anggaran = responseAnggaran.data;



                if (penyelesaian.data !== null && anggaran.data !== null) {
                    document.getElementById(`dropdownDefaultButton1_${index}`).removeAttribute("disabled");
                } else {
                    document.getElementById(`dropdownDefaultButton1_${index}`).setAttribute("disabled", "true");

                }
            };

            Check(item.id);


        });

        data.forEach((_, index, item) => {
            const dropdownButtonMonev = document.getElementById(
                `dropdownDefaultButton1_${index}`
            );
            const dropdownMenuMonev = document.getElementById(
                `dropdown1_${index}`
            );

            const dropdownButtonTarget = document.getElementById(
                `dropdownTargetButton1_${index}`
            );
            const dropdownMenuTarget = document.getElementById(
                `dropdownTarget1_${index}`
            );

            const toggleDropdown = (button, menu) => {
                // Close all dropdowns first
                document.querySelectorAll(".z-10").forEach((dropdown) => {
                    if (dropdown !== menu) {
                        dropdown.classList.add("hidden");
                    }
                });

                // Toggle the current dropdown
                menu.classList.toggle("hidden");
            };

            if (dropdownButtonMonev && dropdownMenuMonev) {
                dropdownButtonMonev.addEventListener("click", () => {
                    toggleDropdown(dropdownButtonMonev, dropdownMenuMonev);
                });
            }

            if (dropdownButtonTarget && dropdownMenuTarget) {
                dropdownButtonTarget.addEventListener("click", () => {
                    toggleDropdown(dropdownButtonTarget, dropdownMenuTarget);
                });
            }

            // Optional: Close dropdowns when clicking outside
            document.addEventListener("click", (e) => {
                if (
                    !dropdownButtonMonev.contains(e.target) &&
                    !dropdownMenuMonev.contains(e.target)
                ) {
                    dropdownMenuMonev.classList.add("hidden");
                }
                if (
                    !dropdownButtonTarget.contains(e.target) &&
                    !dropdownMenuTarget.contains(e.target)
                ) {
                    dropdownMenuTarget.classList.add("hidden");
                }
            });



            document.querySelectorAll(".realisasi-penyelesaian, .realisasi-anggaran, .target-penyelesaian, .target-anggaran").forEach((element) => {
                element.addEventListener("click", (e) => {
                    e.preventDefault(); // Mencegah navigasi default

                    const idRencanaAksi = element.getAttribute("data-id");
                    console.log(idRencanaAksi);

                    localStorage.setItem("id_rencana_aksi", idRencanaAksi);

                    const redirectUrl = element.getAttribute("id"); // Ambil URL dari id
                    console.log(redirectUrl); // Debugging

                    // Lakukan navigasi setelah fungsi dijalankan
                    window.location.href = redirectUrl;
                });
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
                `${baseUrl}/get-rencana-aksi-id`,
                { token:auth, id }
            );


            const data = response.data.data;
            console.log(data);

            document.getElementById("id-renaksi").value = data.id;
            document.getElementById("rencanaAksiEdit").value =
                data.rencana_aksi;
            document.getElementById("namespaceEdit").value = data.unique_namespace;
            document.getElementById("indikatorEdit").value = data.indikator;
            document.getElementById("satuanEdit").value = data.satuan;
            document.getElementById("koordinatorEdit").value = data.koordinator;
            document.getElementById("pelaksanaEdit").value = data.pelaksana;

            document.getElementById("crudModal1").classList.remove("hidden");
        } catch (error) {
            console.error("Failed to load data: ", error);
        }
    };

    document.body.addEventListener("click", async (event) => {
        if (event.target.closest("[data-modal-target='crudModal1']")) {
            const id = event.target
                .closest("[data-id]")
                .getAttribute("data-id");
            await showEditModal(id);
        }
    });

    document.body.addEventListener("click", async (event) => {
        if (event.target.closest("[data-modal-target='popup-modal']")) {
            console.log("hallo");
            const id = event.target
                .closest("[data-id]")
                .getAttribute("data-id");
            document.getElementById("id-delete").value = id;
            document.getElementById("popup-modal1").classList.remove("hidden");
        }
    });

    fetchData();

    searchInput.addEventListener("input", () => {

        currentPage = 1;
        fetchData(currentPage, searchInput.value, perPageSelect.value);
    });

    perPageSelect.addEventListener("change", () => {
        console.log(perPageSelect.value);
        currentPage = 1;
        fetchData(currentPage, searchInput.value, perPageSelect.value);
    });
});
