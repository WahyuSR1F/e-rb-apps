if (typeof baseUrl === 'undefined') {
    // var baseUrl = 'http://192.168.1.5:8010/api';
    var baseUrl = "http://127.0.0.1:8010/api";
}



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



    document.addEventListener('DOMContentLoaded', () => {
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const message = document.getElementById('password-message');
        const submitButton = document.getElementById('submit-button');

        const validatePassword = () => {
            const passwordLengthValid = password.value.length >= 8;
            const passwordsMatch = password.value === passwordConfirmation.value;

            if (passwordConfirmation.value === '') {
                message.style.display = 'none'; // Hide message if confirmation is empty
                submitButton.disabled = true; // Disable button
                return;
            }

            // Clear message and button state
            if (passwordLengthValid && passwordsMatch) {
                message.style.display = 'none';
                submitButton.disabled = false;
            } else {
                message.style.display = 'block';
                if (!passwordLengthValid) {
                    message.textContent = 'Password harus minimal 8 karakter!';
                } else {
                    message.textContent = 'Password tidak cocok!';
                }
                submitButton.disabled = true; // Disable button if validation fails
            }
        };

        password.addEventListener('input', validatePassword);
        passwordConfirmation.addEventListener('input', validatePassword);
    });









// mengambil data

document.addEventListener("DOMContentLoaded", () => {
    auth = document.getElementById('auth_token').value; 
    // Replace with your actual base URL
    const tableBody = document.getElementById("table-body");
    const searchInput = document.getElementById("default-search");
    const perPageSelect = document.getElementById("countries");

    let currentPage = 1;

    // Function to fetch data from API
    const fetchData = async (page = 1, search = "", perPage = 10) => {
        try {
            const response = await axios.get(`${baseUrl}/get-user-paginate`, {
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
                    ${item.username}
                </td>
                 <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                    ${item.nama}
                </td>
                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                    ${item.subidang}
                </td>
                <td class="px-6 py-4">
                    ${item.IdPegawai}
                </td>
                <td class="px-6 py-4">
                    ${item.role}
                </td>
                 <td class="px-6 py-4">
                    ${item.active_user === 1 ? 'Aktif' : 'Tidak Aktif'}
                    
                </td>
                <td class="px-6 py-4">
                 
                    <button data-id="${item.id
                }" data-modal-target="UserModal" data-modal-toggle="UserModal" class="px-2 text-xs my-1 w-full py-3 bg-yellow-400 text-white font-bold text-center hover:bg-yellow-700 rounded">
                        <div class="flex justify-center">
                            <p class="text-gray-200"><i class="fa-regular fa-pen-to-square text-lg text-white"></i></p>
                            <p class="mx-1 mt-2">Edit</p>
                        </div>
                    </button>
                     <button data-id="${item.id
                }" data-modal-target="ResetModal" data-modal-toggle="ResetModal" class="px-2 text-xs my-1 w-full py-3 bg-blue-400 text-white font-bold text-center hover:bg-yellow-700 rounded">
                        <div class="flex justify-center">
                            <p class="text-gray-200"><i class="fa-regular fa-pen-to-square text-lg text-white"></i></p>
                            <p class="mx-1 mt-2">Reset Password</p>
                        </div>
                    </button>
                    <button data-id="${item.id
                }" data-modal-target="popup-modal2" data-modal-toggle="popup-modal2" class="px-2 text-xs my-1 block w-full py-3 bg-red-400 text-white font-bold text-center hover:bg-red-700 rounded">
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

    const showEditModalUser = async (id) => {
        try {
            const response = await axios.get(
                `${baseUrl}/get-user-by-id`,
                {  params:{token:auth, id} }
            );
            const data = response.data.data;
            console.log(data);

            //get value barisnya
            if(data.active_user === 1){
                document.getElementById('status_aktive').textContent = "Aktif";
            }else{
                document.getElementById('status_aktive').textContent = "Non Aktif";
            }
            document.getElementById('status_aktive').value = data.active_user;
              

            document.getElementById('status_aktive').value = data.active_user;
            document.getElementById("id").value = data.id;
            document.getElementById("username").value = data.username;
            document.getElementById("nama").value = data.nama;
            document.getElementById("IdPegawai").value = data.IdPegawai;
            document.getElementById("subidang").value = data.subidang;

            //     data.cluster;
            //get untuk value modal
            const crudModal = document.getElementById("UserModal");
            crudModal.classList.remove("hidden");
        } catch (error) {
            console.error("Failed to load data: ", error);
        }
    };
    const showResetModalUser = async (id) => {
        try {
            const response = await axios.get(
                `${baseUrl}/get-user-by-id`,
                {  params:{token:auth, id} }
            );
            const data = response.data.data;
            console.log(data);

            //get load modal
            document.getElementById('id_reset').value = data.id;
            const crudModal = document.getElementById("ResetModal");
            crudModal.classList.remove("hidden");

            const password = document.getElementById('password_reset');
            const passwordConfirmation = document.getElementById('password_confirmation_reset');
            const message = document.getElementById('password-message_reset');
            const submitButton = document.getElementById('submit-button_reset');
    
            const validatePassword = () => {
                const passwordLengthValid = password.value.length >= 8;
                const passwordsMatch = password.value === passwordConfirmation.value;
    
                if (passwordConfirmation.value === '') {
                    message.style.display = 'none'; // Hide message if confirmation is empty
                    submitButton.disabled = true; // Disable button
                    return;
                }
    
                // Clear message and button state
                if (passwordLengthValid && passwordsMatch) {
                    message.style.display = 'none';
                    submitButton.disabled = false;
                } else {
                    message.style.display = 'block';
                    if (!passwordLengthValid) {
                        message.textContent = 'Password harus minimal 8 karakter!';
                    } else {
                        message.textContent = 'Password tidak cocok!';
                    }
                    submitButton.disabled = true; // Disable button if validation fails
                }
            };
    
            password.addEventListener('input', validatePassword);
            passwordConfirmation.addEventListener('input', validatePassword);


            //     data.cluster;
            //get untuk value modal

        } catch (error) {
            console.error("Failed to load data: ", error);
        }
    };

    //jika tombol di klik
    document.body.addEventListener("click", async (event) => {
        if (event.target.closest("[data-modal-target='UserModal']")) {
            console.log('hello');
            const id = event.target
                .closest("[data-id]")
                .getAttribute("data-id");
            await showEditModalUser(id);
        }
    });

    document.body.addEventListener("click", async (event) => {
        if (event.target.closest("[data-modal-target='ResetModal']")) {
            console.log('hello');
            const id = event.target
                .closest("[data-id]")
                .getAttribute("data-id");
            await showResetModalUser(id);
        }
    });

    //jika tombol delete di klik
    document.body.addEventListener("click", async (event) => {
        if (event.target.closest("[data-modal-target='popup-modal2']")) {
            console.log('hello');
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
