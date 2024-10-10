if (typeof baseUrl === 'undefined') {
    // var baseUrl = 'http://192.168.1.5:8010/api';
    var baseUrl = "http://127.0.0.1:8010/api";
}

const idRenaksi = localStorage.getItem("id_rencana_aksi");
console.log(idRenaksi);


//notify of alert


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
    const idRenaksiInput = document.getElementById("id-renaksi");
    const buttonCreate = document.getElementById("createButton");

    let currentPage = 1;

    const fetchDataMasalah = async (id) => {

        try {
            const response = await axios.post(
                `${baseUrl}/get-rencana-aksi-id`,
                {
                    token:auth,
                    id: id,
                }
            );
            const data = response.data;
            if (!data?.data?.length) {
                buttonCreate.disabled = true;
            } else {
                buttonCreate.disabled = false;
            }
            return data;
        } catch (error) {
            console.error("Request failed:", error);
        }
    };

    const updateTitles = async () => {
        console.log("ini ada id = " + idRenaksi);
        const data = await fetchDataMasalah(idRenaksi);


        if (data) {
            console.log(data);
            detailTitle.textContent = data.data.rencana_aksi; // Ganti 'titleProblem' dengan key yang sesuai dari API
            titleSasaran.textContent = data.data.indikator;
            idRenaksiInput.value = data.data.id; // Ganti 'titleSasaran' dengan key yang sesuai dari API
        } else {

            console.error("Data tidak ditemukan atau terjadi kesalahan.");
        }
    };

    updateTitles();

    const validateInput = (inputElement, targetValue) => {
        const inputValue = parseFloat(inputElement.value);

        if (inputValue > targetValue) {
            inputElement.setCustomValidity(`Nilai tidak boleh lebih dari ${targetValue}`);
            inputElement.reportValidity();
        } else {
            inputElement.setCustomValidity('');
        }

    };

    const addTarget = async () => {
        const data = await axios.post(`${baseUrl}/get-anggaran-id`, {
            token:auth,
            id: idRenaksi
        });


        const targets = data.data.data;
        console.log(targets);

        document.getElementById("twITarget1").textContent = targets.twI;
        document.getElementById("twIITarget1").textContent = targets.twII;
        document.getElementById("twIIITarget1").textContent = targets.twIII;
        document.getElementById("twIVTarget1").textContent = targets.twIV;

        document.getElementById("twICreate").addEventListener("input", function () {
            validateInput(this, targets.twI);
        });
        document.getElementById("twIICreate").addEventListener("input", function () {
            validateInput(this, targets.twII);
        });
        document.getElementById("twIIICreate").addEventListener("input", function () {
            validateInput(this, targets.twIII);
        });
        document.getElementById("twIVCreate").addEventListener("input", function () {
            validateInput(this, targets.twIV);
        });


        document.getElementById("twITarget").textContent = targets.twI;
        document.getElementById("twIITarget").textContent = targets.twII;
        document.getElementById("twIIITarget").textContent = targets.twIII;
        document.getElementById("twIVTarget").textContent = targets.twIV;

        document.getElementById("twIEdit").addEventListener("input", function () {
            validateInput(this, targets.twI);
        });
        document.getElementById("twIIEdit").addEventListener("input", function () {
            validateInput(this, targets.twII);
        });
        document.getElementById("twIIIEdit").addEventListener("input", function () {
            validateInput(this, targets.twIII);
        });
        document.getElementById("twIVEdit").addEventListener("input", function () {
            validateInput(this, targets.twIV);
        });
    }
    addTarget();

    const fetchData = async (page = 1, search = "", perPage = 10) => {
        try {
            const response = await axios.get(`${baseUrl}/get-realisasi-anggaran`, {
                params: {
                    token:auth,
                    id: idRenaksi,
                    page: page,
                    search: search,
                    perPage: perPage,
                },
            });
            const data = response.data;

            if (data.data?.length) {
                buttonCreate.disabled = true;
            } else {
                buttonCreate.disabled = false;
            }

            updateTable(data.data);
            console.log(data);
            // Update the pagination basedo
            updatePagination(data);
        } catch (error) {
            console.error("Request failed:", error);
        }
    };

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
            <th th scope = "row" class= "px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" > 1.</th >
            <td class="px-6 py-4">${item.twI.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            <td class="px-6 py-4">${item.twII.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            <td class="px-6 py-4">${item.twIII.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            <td class="px-6 py-4">${item.twIV.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            <td class="px-6 py-4">${item.jumlah.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            <td class="px-6 py-4">${item.capaian}</td>
            <td class="px-6 py-4">${parseFloat(item.presentase).toFixed(2)}%</td>
            <td class="px-6 py-4">
                <button data-id="${item.id}"  data-modal-target="crudModal1" data-modal-toggle="crudModal1"  class="px-2 text-xs my-1 w-full  py-3 bg-yellow-400 text-white font-bold text-center hover:bg-yellow-700 rounded">
                    <div class="flex justify-center">
                        <p class="text-gray-200"><i class="fa-regular fa-pen-to-square text-lg text-white"></i></p>
                        <p class="mx-1 mt-2">Edit</p>
                    </div>
                </button>
                <button data-id="${item.id}"  data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="px-2 text-xs my-1 block w-full  py-3 bg-red-400 text-white font-bold text-center hover:bg-red-700 rounded">
                    <div class="flex justify-center">
                        <p class="text-gray-200"><i class="fa-regular fa-trash-can text-white text-lg"></i></p>
                        <p class="mx-1 mt-2">Delete</p>
                    </div>
                </button>
            </td>
           
      
            `;

            tableBody.appendChild(row);
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
                <a a href = "#" class= "flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" >
                    <span class="sr-only">Previous</span>
                    <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                    </svg>
                </a >
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
        <a a href = "#" class="flex items-center justify-center px-4 h-10 leading-tight ${i === currentPage
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
<a a href = "#" class= "flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" >
                    <span class="sr-only">Next</span>
                    <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                </a >
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
                `${baseUrl}/get-realisasi-anggaran-id`,
                {token:auth, id }
            );


            const data = response.data.data;
            console.log('ini data edit');
            console.log(data);



            document.getElementById("idRealisasiPenyelesaian").value = data.id;
            document.getElementById("idRencanaAksi").value = data.rencana_aksi_id;
            document.getElementById("twIEdit").value = data.twI;
            document.getElementById("twIIEdit").value = data.twII;
            document.getElementById("twIIIEdit").value = data.twIII;
            document.getElementById("twIVEdit").value = data.twIV;


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
            console.log(id);
            await showEditModal(id);
        }
    });

    document.body.addEventListener("click", async (event) => {
        if (event.target.closest("[data-modal-target='popup-modal']")) {
            const id = event.target
                .closest("[data-id]")
                .getAttribute("data-id");
            console.log(id);
            document.getElementById("id-delete").value = id;
            document.getElementById("popup-modal").classList.remove("hidden");
        }
    });

    // jika tidak ada inputan yang dimasukan

    fetchData();

    // jika ada intputan search dan page yang diganti

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