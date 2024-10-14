if (typeof baseUrl === 'undefined') {
    // var baseUrl = 'http://192.168.1.5:8010/api';
    var baseUrl = "http://127.0.0.1:8010/api";
}
let auth, idTema, idCluster;
 // Replace with your actual base URL

// Function to fetch and update table data
async function fetchData() {
    try {
        const response = await axios.get(`${baseUrl}/get-eval-rb`, {
            params: { token: auth, id: idTema },
        });
        updateTable(response.data.data);
    } catch (error) {
        console.error("Request failed:", error);
    }
}

// Function to update table with data
function updateTable(data) {
    const tableBody = document.getElementById("table-body");
    tableBody.innerHTML = ""; // Clear existing rows
    
    data.forEach((items, index) => {
        let renaksi = items.all_related_data;
        renaksi.forEach((item) => {
            const row = createTableRow(items, item, index);
            tableBody.appendChild(row);
            updateButtonVisibility(row);
        });
    });

    // Add event listener for file modals
    addFileModalEventListener();
}

// Function to create a table row
function createTableRow(items, item, index) {
    const row = document.createElement("tr");
    row.classList.add("border", "table-row");
    row.setAttribute("data-id", item.id);
    
    row.innerHTML = `
        <td class="py-2 px-4 border text-center">${index + 1}</td>
        <td data-name="permasalahan" class="py-2 px-4 border text-center">${items.pembuat?.nama ?? ''}</td>
        <td data-name="permasalahan" class="py-2 px-4 border">${items.permasalahan ?? ''}</td>
        <td data-name="permasalahan" class="py-2 px-4 border text-center">${
                                    items.unique_namespace ?? null
                                }</td>
                                <td data-name="sasaran" class="py-2 px-4 border">${
                                    items.sasaran ?? null
                                }</</td>
                                <td data-name="indikator" class="py-2 px-4 border">${
                                    items.indikator ?? null
                                }</td>
                                <td data-name="target" class="py-2 px-4 border text-center">${
                                    items.target ?? null
                                }</td>
                                <td data-name="rencana_aksi" class="py-2 px-4 border">${
                                    item.rencana_aksi ?? null
                                }</td>
                                <td data-name="permasalahan" class="py-2 px-4 border text-center">${
                                    item.unique_namespace ?? null
                                }</td>
                                <td data-name="indikator_rencana_aksi" class="py-2 px-4 border">${
                                    item.indikator ?? null
                                }</td>
                                <td data-name="satuan" class="py-2 px-4 border text-center">${
                                    item.satuan ?? null
                                }</td>
                                <td data-name="twI_target_penyelesaian" class="py-2 px-4 border text-center">${
                                    item.target_penyelesaian?.twI ?? null
                                }</td>
                                <td data-name="twII_target_penyelesaian" class="py-2 px-4 border text-center">${
                                    item.target_penyelesaian?.twII ?? null
                                }</td>
                                <td data-name="twIII_target_penyelesaian" class="py-2 px-4 border text-center">${
                                    item.target_penyelesaian?.twIII ?? null
                                }</td>
                                <td data-name="twIV_target_penyelesaian" class="py-2 px-4 border text-center">${
                                    item.target_penyelesaian?.twIV ?? null
                                }</td>
                                <td data-name="jumlah_target_penyelesaian" class="py-2 px-4 border text-center">${
                                    item.target_penyelesaian?.jumlah ?? null
                                }</td>
                                <td data-name="twI_realisasi_penyelesaian" class="py-2 px-4 border text-center">${
                                    item.realisasi_penyelesaian?.twI ?? null
                                }</td>
                                <td data-name="twII_realisasi_penyelesaian" class="py-2 px-4 border text-center">${
                                    item.realisasi_penyelesaian?.twII ?? null
                                }</td>
                                <td data-name="twIII_realisasi_penyelesaian" class="py-2 px-4 border text-center">${
                                    item.realisasi_penyelesaian?.twIII ?? null
                                }</td>
                                <td data-name="twIV_realisasi_penyelesaian" class="py-2 px-4 border text-center">${
                                    item.realisasi_penyelesaian?.twIV ?? null
                                }</td>
                                <td data-name="jumlah_realisasi_penyelesaian" class="py-2 px-4 border text-center">${
                                    item.realisasi_penyelesaian?.jumlah ?? null
                                }</td>
                                <td data-name="presentase_realisasi_penyelesaian" class="py-2 px-4 border text-center">${
                                    item.realisasi_penyelesaian?.presentase ?? null
                                }</td>
                                <td data-name="subjek" class="py-2 px-4 border text-center">${
                                    item.target_penyelesaian?.subjek ?? null
                                }</td>
                                <td data-name="twI_target_anggaran" class="py-2 px-4 border text-center">${
                                    item.target_anggaran?.twI ?? null
                                }</td>
                                <td data-name="twII_target_anggaran" class="py-2 px-4 border text-center">${
                                    item.target_anggaran?.twII ?? null
                                }</td>
                                <td data-name="twIII_target_anggaran" class="py-2 px-4 border text-center">${
                                    item.target_anggaran?.twIII ?? null
                                }</td>
                                <td data-name="twIV_target_anggaran" class="py-2 px-4 border text-center">${
                                    item.target_anggaran?.twIV ?? null
                                }</td>
                                <td data-name="jumlah_target_anggaran" class="py-2 px-4 border text-center">${
                                    item.target_anggaran?.jumlah ?? null
                                }</td>
                                <td data-name="twI_realisasi_anggaran" class="py-2 px-4 border text-center">${
                                    item.realisasi_anggaran?.twI ?? null
                                }</td>
                                <td data-name="twII_realisasi_anggaran" class="py-2 px-4 border text-center">${
                                    item.realisasi_anggaran?.twII ?? null
                                }</td>
                                <td data-name="twIII_realisasi_anggaran" class="py-2 px-4 border text-center">${
                                    item.realisasi_anggaran?.twIII ?? null
                                }</td>
                                <td data-name="twIV_realisasi_anggaran" class="py-2 px-4 border text-center">${
                                    item.realisasi_anggaran?.twIV ?? null
                                }</td>
                                <td data-name="jumlah_realisasi_anggaran" class="py-2 px-4 border text-center">${
                                    item.realisasi_anggaran?.jumlah ?? null
                                }</td>
                                <td data-name="presentase_realisasi_anggaran" class="py-2 px-4 border text-center">${
                                    item.realisasi_anggaran?.presentase ?? null
                                }</td>
                                <td data-name="koordinator" class="py-2 px-4 border text-center">${
                                    item.koordinator ?? null
                                }</td>
                                <td data-name="pelaksana" class="py-2 px-4 border text-center">${
                                    item.pelaksana ?? null
                                }</td>
                                <td data-name="document" class="py-2 px-4 border text-center">
                                    ${item.file_assets ? `
                                        <button 
                                            data-id="${item.file_assets && item.file_assets.file_path ? item.file_assets.file_path : ''
                                            }/${item.file_assets && item.file_assets.file_name ? item.file_assets.file_name : ''
                                            }"
                                            data-modal-target="ShowFile"
                                            data-modal-toggle="ShowFile"
                                            class="px-3 py-2 w-50% bg-blue-500 text-white text-center hover:bg-blue-700 rounded">
                                            <div class="flex justify-center">
                                                <p class="text-gray-200"><i class="fa-regular fa-file text-sm text-white"></i></p>
                                                <p class="mx-1 tex-sm font-semibold">File</p>
                                            </div>
                                        </button>
                                    ` : 'Tidak Ada File Documentasi'}
                                </td>
        <td data-name="status" class="status-cell py-2 px-3 border text-center">
            ${getStatusBadge(item.reject ? item.reject.status : null)}
        </td>
        <td class="py-2 px-4 checkbox-center flex flex-wrap gap-2">
            <button data-id="${items.id}"
                class="bg-green-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-green-600 approve-btn"
                onclick="approveRow(this.closest('tr'), '${item.id}')">Approve</button>
            <button data-id="${item.id}"
                class="bg-red-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-red-600 reject-btn"
                onclick="toggleEditButton(this.closest('tr'))">Reject</button>
            <button data-id="${item.id}"
                class="bg-blue-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-blue-600 edit-btn hidden"
                onclick="saveRow(this.closest('tr'), '${items.id}', '${item.id}')">Save</button>
            <button data-id="${item.id}"
                class="bg-yellow-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-yellow-600 reassign-btn hidden"
                onclick="reassignRow(this.closest('tr'), '${item.id}', '${items.id}')">Reassign</button>
        </td>
    `;
    
    return row;
}

// Function to update button visibility
function updateButtonVisibility(row) {
    const statusCell = row.querySelector('[data-name="status"]');
    const approveBtn = row.querySelector(".approve-btn");
    const rejectBtn = row.querySelector(".reject-btn");
    const saveBtn = row.querySelector(".edit-btn");
    const reassignBtn = row.querySelector(".reassign-btn");

    const status = statusCell.textContent.trim();

    if (status === "Approved" || status === "Rejected") {
        approveBtn.style.display = "none";
        rejectBtn.style.display = "none";
        saveBtn.style.display = "none";
        reassignBtn.style.display = "none";
    } else {
        approveBtn.style.display = "inline-block";
        rejectBtn.style.display = "inline-block";
        saveBtn.style.display = "none";
        reassignBtn.style.display = "none";
    }
}

// Function to handle row approval
async function approveRow(row, idRenaksi) {
    if (confirm("Are you sure you want to approve this data?")) {
        try {
            const response = await axios.post(`${baseUrl}/approve-by-admin`, {
                token: auth,
                id: idRenaksi,
            });

            console.log("Data approved successfully:", response.data);
            alert("Data approved successfully.");
            
            // Refresh data after approval
            await fetchData();
        } catch (error) {
            console.error("Error approving data:", error);
            alert("Failed to approve data. Please try again.");
        }
    }
}

// Function to handle row saving
async function saveRow(row, idPermasalahan, idRenaksi) {
    const inputs = row.querySelectorAll("input");
    const rowData = {
        token: auth,
        idPermasalahan: idPermasalahan,
        idRenaksi: idRenaksi,
    };

    inputs.forEach((input) => {
        rowData[input.name] = input.value;
    });

    try {
        const response = await axios.post(`${baseUrl}/update-by-admin`, rowData);
        console.log("Data saved successfully:", response.data);
        alert("Data saved successfully.");
        
        // Refresh data after saving
        await fetchData();
    } catch (error) {
        console.error("Error saving data:", error);
        alert("Failed to save data. Please try again.");
    }
}

// Function to toggle edit mode
function toggleEditButton(row) {
    const rejectBtn = row.querySelector(".reject-btn");
    const editBtn = row.querySelector(".edit-btn");
    const reassignBtn = row.querySelector(".reassign-btn");
    const isEditable = row.classList.contains("editable");

    if (isEditable) {
        // Cancel edit mode
        row.classList.remove("editable");
        rejectBtn.textContent = "Reject";
        editBtn.style.display = "none";
        reassignBtn.classList.add("hidden");
        cancelEdit(row);
    } else {
        // Enter edit mode
        row.classList.add("editable");
        rejectBtn.textContent = "Cancel";
        editBtn.style.display = "block";
        reassignBtn.style.display = "block";
        makeRowEditable(row);
    }
}

// Function to make a row editable
function makeRowEditable(row) {
    const cells = row.querySelectorAll("[data-name]");
    cells.forEach((cell) => {
        const columnName = cell.getAttribute("data-name");
        if (!["status", "document"].includes(columnName)) {
            const input = document.createElement("input");
            input.type = columnName.includes("tw") ? "number" : "text";
            input.name = columnName;
            input.value = cell.textContent;
            input.classList.add("border", "border-black", "w-full");
            cell.textContent = '';
            cell.appendChild(input);
        }
    });
}

// Function to cancel edit mode
function cancelEdit(row) {
    const cells = row.querySelectorAll("[data-name]");
    cells.forEach((cell) => {
        const input = cell.querySelector("input");
        if (input) {
            cell.textContent = input.value;
        }
    });
    fetchData(); // Refresh data to ensure we have the latest server data
}

// Function to handle row reassignment
async function reassignRow(row, idRenaksi, idPermasalahan) {
    currentRow = row;
    currentIdRenaksi = idRenaksi;
    currentIdPermasalahan = idPermasalahan;
    document.getElementById("reassignModal").style.display = "flex";
}

// Function to submit revision
async function submitRevision() {
    const note = document.getElementById("revisionNote").value;
    if (note.trim() === "") {
        alert("Catatan revisi tidak boleh kosong.");
        return;
    }
    try {
        const response = await axios.post(`${baseUrl}/reject-by-admin`, {
            token: auth,
            idRenaksi: currentIdRenaksi,
            idPermasalahan: currentIdPermasalahan,
            note: note,
        });

        console.log("Revision submitted successfully:", response.data);
        alert("Catatan revisi berhasil dikirim ke user.");

        closeModal();

        // Refresh data after reassignment
        await fetchData();
    } catch (error) {
        console.error("Error submitting revision:", error);
        alert("Gagal mengirim catatan revisi. Silakan coba lagi.");
    }
}

// Function to close modal
function closeModal() {
    document.getElementById("reassignModal").style.display = "none";
}

// Function to get status badge HTML
function getStatusBadge(status) {
    if (status === 'Approved') {
        return `<span class="bg-green-500 text-white px-3 py-2 rounded">${status}</span>`;
    } else if (status === 'Pending') {
        return `<span class="bg-yellow-500 text-white px-3 py-2 rounded">${status}</span>`;
    } else if (status === 'Rejected') {
        return `<span class="bg-red-500 text-white px-3 py-2 rounded">${status}</span>`;
    } else {
        return `<span class="bg-gray-500 text-white px-3 py-2 rounded">Normal</span>`;
    }
}

// Function to load clusters
async function loadClusters(auth) {
    try {
        const response = await axios.get(`${baseUrl}/get-cluster`, {
            params: { token: auth }
        });
        const clusters = response.data.data;
        const selectElement = document.getElementById("cluster-select");
        selectElement.innerHTML = '<option value="">Pilih Cluster</option>';
        clusters.forEach((cluster) => {
            const option = document.createElement("option");
            option.value = cluster.id;
            option.textContent = cluster.cluster;
            option.id = cluster.cluster;
            selectElement.appendChild(option);
        });
    } catch (error) {
        console.error("Error loading clusters:", error);
    }
}

// Function to load tema
async function loadTema(clusterId, auth) {
    try {
        const params = {
            token: auth,
            id: clusterId,
        };
        const response = await axios.get(`${baseUrl}/get-tema`, { params });
        const temas = response.data.data;
        const selectElement = document.getElementById("theme-select");
        selectElement.innerHTML = '<option value="">Pilih Tema</option>';
        temas.forEach((tema) => {
            const option = document.createElement("option");
            option.value = tema.id;
            option.textContent = tema.nama;
            selectElement.appendChild(option);
        });
    } catch (error) {
        console.error("Error loading tema:", error);
    }
}

// Function to add file modal event listener
function addFileModalEventListener() {
    document.body.addEventListener("click", async (event) => {
        const modalTrigger = event.target.closest("[data-modal-target='ShowFile']");
        if (modalTrigger) {
            const modal = document.getElementById("ShowFile");
            
            // Show the modal
            showModal(modal);

            const id = decodeURIComponent(modalTrigger.getAttribute("data-id"));
            const encodedId = id.replace(/\s/g, '%20').replace(/\+/g, '%2B');

            try {
                const response = await axios.get(baseUrl + `/tes-getgd?id=${encodedId}`, {
                    params: { token: auth },
                    responseType: 'blob'
                });

                if (response.headers['content-type'] !== 'application/pdf') {
                    throw new Error('Received non-PDF response');
                }
                const blob = new Blob([response.data], { type: 'application/pdf' });
                const url = URL.createObjectURL(blob);
                const iframe = document.getElementById('pdfViewer');
                iframe.src = url;
            } catch (error) {
                console.error('Axios error:', error);
                // Handle error (e.g., show error message in modal)
            }
        }
    });

    // Add event listener for closing the modal
    const closeButton = document.querySelector("[data-modal-toggle='ShowFile']");
    if (closeButton) {
        closeButton.addEventListener("click", () => {
            const modal = document.getElementById("ShowFile");
            hideModal(modal);
        });
    }

    // Close modal when clicking outside
    window.addEventListener("click", (event) => {
        const modal = document.getElementById("ShowFile");
        if (event.target === modal) {
            hideModal(modal);
        }
    });
}

function showModal(modal) {
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    document.body.classList.add("overflow-hidden"); // Prevent scrolling when modal is open
}

function hideModal(modal) {
    modal.classList.add("hidden");
    modal.classList.remove("flex");
    document.body.classList.remove("overflow-hidden");
    
    // Clear the iframe src when closing the modal
    const iframe = document.getElementById('pdfViewer');
    iframe.src = '';
}

// Call this function when the page loads

// Event listener for DOMContentLoaded
document.addEventListener("DOMContentLoaded", async () => {
    auth = document.getElementById('auth_token').value;
    
    loadClusters(auth);
    addFileModalEventListener();

    document.getElementById("cluster-select").addEventListener("change", function() {
        idCluster = this.value;
        const themeDropdown = document.getElementById("theme-dropdown");

        if (idCluster) {
            themeDropdown.classList.remove("hidden");
            loadTema(idCluster, auth);
        } else {
            themeDropdown.classList.add("hidden");
            document.getElementById("refresContainer").classList.add("hidden");
        }
    });

    document.getElementById("theme-select").addEventListener("change", function() {
        idTema = this.value;
        const refreshContainer = document.getElementById("refresContainer");

        if (idTema) {
            refreshContainer.classList.remove("hidden");
            fetchData();
        } else {
            refreshContainer.classList.add("hidden");
        }
    });

    document.getElementById("export-button").addEventListener("click", function(event) {
        event.preventDefault();
        
        if (typeof idTema === 'undefined' || !idTema) {
            alert('Please select a theme ID to export data.');
        } else {
            window.location.href = `/export-approve?id=${idTema}`;
        }
    });

    document.getElementById("refreshButton").addEventListener("click", fetchData);
});