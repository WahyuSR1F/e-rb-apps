let currentRow = null;
let originalValues = []; // Array untuk menyimpan nilai awal baris sebelum di-edit

// Function to handle row approval
function approveRow(row) {
    // Example: Display a confirmation alert
    if (confirm("Are you sure you want to approve this data?")) {
        // Implement the logic to approve the data here
        alert("Data approved successfully.");
        // Optionally, you can hide or disable the buttons once approved
        row.querySelector(".approve-btn").classList.add("hidden");
        row.querySelector(".reject-btn").classList.add("hidden");
        row.querySelector(".edit-btn").classList.add("hidden");
        row.querySelector(".reassign-btn").classList.add("hidden");
    }
}

// Fungsi untuk toggle Edit button dan mengedit baris
function toggleEditButton(row) {
    const editBtn = row.querySelector(".edit-btn");
    const rejectBtn = row.querySelector(".reject-btn");
    const reassignBtn = row.querySelector(".reassign-btn"); // New Reassign button
    const noteArea = row.querySelector(".note-area"); // Area catatan hasil revisi
    const isEditable = row.classList.contains("editable");

    if (isEditable) {
        // Kembalikan nilai asli jika admin tidak ingin mengedit
        restoreOriginalRow(row);
        row.classList.remove("editable");
        rejectBtn.textContent = "Reject";
        editBtn.style.display = "none"; // Sembunyikan tombol save lagi
        reassignBtn.classList.add("hidden"); // Sembunyikan tombol Reassign
        noteArea.classList.add("hidden"); // Show note area when editing
    } else {
        // Simpan nilai asli sebelum di-edit
        storeOriginalRow(row);
        makeRowEditable(row);
        row.classList.add("editable");
        rejectBtn.textContent = "Cancel"; // Ubah teks tombol "Reject" menjadi "Cancel"
        editBtn.style.display = "block"; // Tampilkan tombol Save
        reassignBtn.classList.remove("hidden"); // Tampilkan tombol Reassign
        noteArea.classList.remove("hidden"); // Show note area when editing
    }
}

// Fungsi untuk menyimpan nilai asli sebelum diedit
function storeOriginalRow(row) {
    originalValues = [];
    const cells = row.querySelectorAll("td");
    for (let i = 1; i < cells.length - 1; i++) {
        // Lewatkan kolom pertama (No) dan terakhir (Aksi)
        originalValues.push(cells[i].innerText.trim());
    }
}

// Fungsi untuk mengembalikan nilai asli jika admin tidak jadi mengedit
function restoreOriginalRow(row) {
    const cells = row.querySelectorAll("td");
    for (let i = 1; i < cells.length - 1; i++) {
        // Lewatkan kolom pertama (No) dan terakhir (Aksi)
        cells[i].innerText = originalValues[i - 1]; // Kembalikan nilai asli dari array originalValues
    }
}

// Fungsi untuk membuat baris bisa diedit
function makeRowEditable(row) {
    const cells = row.querySelectorAll("td");
    for (let i = 1; i < cells.length - 1; i++) {
        // Lewatkan kolom pertama (No) dan terakhir (Aksi)
        const currentValue = cells[i].innerText.trim();
        cells[
            i
        ].innerHTML = `<input type="text" class="form-input w-full" value="${currentValue}">`;
    }
}

// Fungsi untuk menyimpan baris yang sudah diedit
function saveRow(row) {
    const inputs = row.querySelectorAll("input");
    inputs.forEach((input) => {
        const td = input.closest("td");
        td.innerText = input.value;
    });
    row.querySelector(".edit-btn").style.display = "none"; // Sembunyikan tombol save setelah menyimpan
    row.classList.remove("editable"); // Hilangkan kelas editable
    row.querySelector(".reject-btn").textContent = "Reject"; // Kembalikan teks tombol Reject
    row.querySelector(".reassign-btn").classList.add("hidden"); // Sembunyikan tombol Reassign
    row.querySelector(".note-area").style.display = "none"; // Sembunyikan area catatan
}

// Fungsi untuk meng-handle aksi Reassign
function reassignRow(row) {
    currentRow = row;
    document.getElementById("reassignModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("reassignModal").style.display = "none";
}

function submitRevision() {
    const note = document.getElementById("revisionNote").value;
    if (note.trim() === "") {
        alert("Catatan revisi tidak boleh kosong.");
        return;
    }
    alert("Catatan revisi berhasil dikirim ke user.");

    // Menghapus baris setelah reassigned
    currentRow.remove();
    closeModal();
}

// Function to download table data as Excel file
function downloadData() {
    const table = document.getElementById("approvedDataTable");
    const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
    XLSX.writeFile(wb, "stunting_data.xlsx"); // File name
}
