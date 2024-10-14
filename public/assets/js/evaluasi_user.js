if (typeof baseUrl === "undefined") {
    var baseUrl = "http://127.0.0.1:8010/api";
}

// Variabel global
const itemsPerPage = 10;
let currentPage = 1;

auth = document.getElementById("auth_token").value;

onload = () => {
    loadData();
};

// fungsi load data
const loadData = async () => {
    try {
        const response = await axios.get(`${baseUrl}/get-evaluasi/${user_id}`);
        displayData(response.data.data, currentPage);
        setupPagination(response.data.data.length, itemsPerPage);
    } catch (error) {
        console.error("Failed to load data: ", error);
    }
};

function displayData(data, page) {
    const start = (page - 1) * itemsPerPage;
    const end = page * itemsPerPage;
    const paginatedItems = data.slice(start, end);
    console.log(paginatedItems);

    // Dapatkan elemen tabel di mana data akan ditampilkan
    const tableBody = document.getElementById("table-body");

    // Kosongkan tabel sebelumnya
    tableBody.innerHTML = "";

    console.log(data);

    paginatedItems.forEach((data, index) => {
        // Tambahkan data ke tabel
        const row = document.createElement("tr");
        row.classList.add("border-b", "table-row");

        // Tambahkan sel ke baris
        const id = row.appendChild(makecell(data.id, "id"));
        id.classList.add("hidden");
        row.appendChild(makecell(index + 1, "no"));
        row.appendChild(makecell(data.permasalahan, "permasalahan"));
        row.appendChild(makecell(data.sasaran, "sasaran"));
        row.appendChild(makecell(data.indikator, "indikator"));
        row.appendChild(makecell(data.target, "target"));

        paginatedItems[index].renaksi.forEach((data) => {
            row.appendChild(makecell(data.rencana_aksi, "rencana-aksi"));
            row.appendChild(makecell(data.indikator, "rencanaAksi-indikator"));
            row.appendChild(makecell(data.satuan, "rencanaAksi-satuan"));
            if (data.target_penyelesaian != null) {
                row.appendChild(
                    makecell(
                        data.target_penyelesaian.twI,
                        "target-penyelesaian-twI"
                    )
                );
                row.appendChild(
                    makecell(
                        data.target_penyelesaian.twII,
                        "target-penyelesaian-twII"
                    )
                );
                row.appendChild(
                    makecell(
                        data.target_penyelesaian.twIII,
                        "target-penyelesaian-twIII"
                    )
                );
                row.appendChild(
                    makecell(
                        data.target_penyelesaian.twIV,
                        "target-penyelesaian-twIV"
                    )
                );
                row.appendChild(
                    makecell(
                        data.target_penyelesaian.jumlah,
                        "target-penyelesaian-total"
                    )
                );
            } else {
                row.appendChild(makecell("0", "target-penyelesaian-twI"));
                row.appendChild(makecell("0", "target-penyelesaian-twII"));
                row.appendChild(makecell("0", "target-penyelesaian-twIII"));
                row.appendChild(makecell("0", "target-penyelesaian-twIV"));
                row.appendChild(makecell("0", "target-penyelesaian-total"));
            }
            if (data.realisasi_penyelesaian != null) {
                row.appendChild(
                    makecell(
                        data.realisasi_penyelesaian.twI,
                        "realisasi-penyelesaian-twI"
                    )
                );
                row.appendChild(
                    makecell(
                        data.realisasi_penyelesaian.twII,
                        "realisasi-penyelesaian-twII"
                    )
                );
                row.appendChild(
                    makecell(
                        data.realisasi_penyelesaian.twIII,
                        "realisasi-penyelesaian-twIII"
                    )
                );
                row.appendChild(
                    makecell(
                        data.realisasi_penyelesaian.twIV,
                        "realisasi-penyelesaian-twIV"
                    )
                );
                row.appendChild(
                    makecell(
                        data.realisasi_penyelesaian.jumlah,
                        "realisasi-penyelesaian-jumlah"
                    )
                );
                row.appendChild(
                    makecell(
                        formatPersen(
                            data.realisasi_penyelesaian.presentase,
                            "realisasi-penyelesaian-capaian"
                        )
                    )
                );
            } else {
                row.appendChild(makecell("0", "realisasi-penyelesaian-twI"));
                row.appendChild(makecell("0", "realisasi-penyelesaian-twII"));
                row.appendChild(makecell("0", "realisasi-penyelesaian-twIII"));
                row.appendChild(makecell("0", "realisasi-penyelesaian-twIV"));
                row.appendChild(makecell("0", "realisasi-penyelesaian-jumlah"));
                row.appendChild(
                    makecell("0", "realisasi-penyelesaian-capaian")
                );
            }
            if (data.target_penyelesaian != null) {
                if (data.target_penyelesaian.type != null) {
                    row.appendChild(
                        makecell(
                            data.target_penyelesaian.type,
                            "target-penyelesaian-type"
                        )
                    );
                } else {
                    row.appendChild(makecell("-", "target-penyelesaian-type"));
                }
            } else {
                row.appendChild(makecell("-", "target-penyelesaian-type"));
            }

            if (data.target_anggaran != null) {
                row.appendChild(
                    makecell(
                        formatRupiah(data.target_anggaran.twI),
                        "target-anggaran-twI"
                    )
                );
                row.appendChild(
                    makecell(
                        formatRupiah(data.target_anggaran.twII),
                        "target-anggaran-twII"
                    )
                );
                row.appendChild(
                    makecell(
                        formatRupiah(data.target_anggaran.twIII),
                        "target-anggaran-twIII"
                    )
                );
                row.appendChild(
                    makecell(
                        formatRupiah(data.target_anggaran.twIV),
                        "target-anggaran-twIV"
                    )
                );
                row.appendChild(
                    makecell(
                        formatRupiah(data.target_anggaran.jumlah),
                        "target-anggaran-jumlah"
                    )
                );
            } else {
                row.appendChild(makecell("0", "target-anggaran-twI"));
                row.appendChild(makecell("0", "target-anggaran-twII"));
                row.appendChild(makecell("0", "target-anggaran-twIII"));
                row.appendChild(makecell("0", "target-anggaran-twIV"));
                row.appendChild(makecell("0", "target-anggaran-jumlah"));
            }
            if (data.realisasi_anggaran != null) {
                row.appendChild(
                    makecell(
                        formatRupiah(data.realisasi_anggaran.twI),
                        "realisasi-anggaran-twI"
                    )
                );
                row.appendChild(
                    makecell(
                        formatRupiah(data.realisasi_anggaran.twII),
                        "realisasi-anggaran-twII"
                    )
                );
                row.appendChild(
                    makecell(
                        formatRupiah(data.realisasi_anggaran.twIII),
                        "realisasi-anggaran-twIII"
                    )
                );
                row.appendChild(
                    makecell(
                        formatRupiah(data.realisasi_anggaran.twIV),
                        "realisasi-anggaran-twIV"
                    )
                );
                row.appendChild(
                    makecell(
                        formatRupiah(data.realisasi_anggaran.jumlah),
                        "realisasi-anggaran-jumlah"
                    )
                );
                row.appendChild(
                    makecell(
                        formatPersen(
                            data.realisasi_anggaran.presentase,
                            "realisasi-anggaran-capaian"
                        )
                    )
                );
            } else {
                row.appendChild(makecell("0", "realisasi-anggaran-twI"));
                row.appendChild(makecell("0", "realisasi-anggaran-twII"));
                row.appendChild(makecell("0", "realisasi-anggaran-twIII"));
                row.appendChild(makecell("0", "realisasi-anggaran-twIV"));
                row.appendChild(makecell("0", "realisasi-anggaran-jumlah"));
                row.appendChild(makecell("0", "realisasi-anggaran-capaian"));
            }
            row.appendChild(
                makecell(data.koordinator, "rencanaAksi-koordinator")
            );
            row.appendChild(makecell(data.pelaksana, "rencanaAksi-pelaksana"));
            row.appendChild(
                makeStatus(
                    data.reject.status,
                    "reject-status",
                    data.reject.status == "Rejected"
                        ? "bg-red-500"
                        : "bg-gray-400"
                )
            );
            row.appendChild(makecell(data.reject.comment, "reject-comment"));

            // Tambahkan tombol edit dan save
            if (data.reject.status == "Rejected") {
                row.appendChild(
                    makeActionButton(
                        "edit",
                        "bg-yellow",
                        "save",
                        "bg-green",
                        row
                    )
                );
            }
        });

        tableBody.appendChild(row);
    });
}

// Fungsi untuk membuat tombol navigasi halaman
function setupPagination(totalItems, itemsPerPage) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const paginationContainer = document.getElementById("pagination");
    paginationContainer.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        const button = createPaginationButton(i);
        paginationContainer.appendChild(button);
    }
}

// Fungsi untuk membuat tombol pagination
function createPaginationButton(page) {
    const button = document.createElement("button");
    button.classList.add(
        "mx-1",
        "inline-block",
        "rounded",
        "bg-white",
        "text-black",
        "font-semibold",
        "py-2",
        "px-4",
        "hover:bg-gray-100",
        "cursor-pointer"
    );
    button.textContent = page;

    if (page === currentPage) {
        button.classList.remove("bg-white", "text-black", "hover:bg-gray-100");
        button.classList.add(
            "active",
            "text-white",
            "bg-blue-500",
            "font-bold",
            "hover:bg-blue-600"
        );
    }

    button.addEventListener("click", function () {
        currentPage = page;
        loadData();

        // Update tombol active
        const currentActive = document.querySelector(".pagination .active");
        if (currentActive) {
            currentActive.classList.remove("active");
        }
        button.classList.add("active");
    });

    return button;
}

// fungsi untuk membuat cell
function makecell(cellvalue, name) {
    const Cell = document.createElement("td");
    Cell.classList.add("py-2", "px-4", "cell", "border", "text-center", name);
    Cell.textContent = cellvalue;

    return Cell;
}

function makeStatus(cellvalue, name, color) {
    const Cell = document.createElement("td");
    Cell.classList.add("py-2", "px-4", "cell", name);
    // Create a span element
    const Span = document.createElement("span");

    // Add color to the span (red in this case)
    Span.classList.add("p-1", "text-white", "rounded-lg", color); // If no color is passed, default to red

    // Set the text content of the span
    Span.textContent = cellvalue;

    // Append the span to the Cell
    Cell.appendChild(Span);
    return Cell;
}

function formatRupiah(amount) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(amount);
}

function formatPersen(value) {
    if (value == null || isNaN(value)) {
        return "0%";
    }

    const formatter = new Intl.NumberFormat("en-US", {
        style: "percent",
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
    });

    return formatter.format(value / 100);
}

function makeActionButton(value1, color1, value2, color2, row) {
    const Cell = document.createElement("td");
    Cell.classList.add("py-2", "px-4");
    Cell.innerHTML =
        `<button class="` +
        color1 +
        `-400 hover:` +
        color1 +
        `-600 text-white font-bold py-2 px-4 rounded-lg editing-button">` +
        value1 +
        `</button>

            <button class="` +
        color2 +
        `-500 hover:` +
        color2 +
        `-700 text-white font-bold py-2 px-4 rounded-lg mt-3 save-button hidden">` +
        value2 +
        `</button>`;

    const button = Cell.querySelector(".editing-button");
    const saveButton = Cell.querySelector(".save-button");

    button.onclick = function () {
        row.classList.toggle("editable");
        saveButton.classList.toggle("hidden");
        button.classList.toggle("bg-red-600");
        button.classList.toggle("bg-yellow-400");
        button.classList.toggle("hover:bg-yellow-600");
        button.classList.toggle("hover:bg-red-800");

        if (button.textContent == "cancle") {
            button.textContent = "edit";
        } else {
            button.textContent = "cancle";
        }

        const isEditable = row.classList.contains("editable");
        if (!isEditable) {
            // Kembalikan nilai asli jika admin tidak ingin mengedit
            restoreOriginalRow(row);
            row.classList.remove("editable");
        } else {
            // Simpan nilai asli sebelum di-edit
            storeOriginalRow(row);
            makeRowEditable(row);
        }
    };

    saveButton.onclick = function () {
        row.classList.remove("editable");
        saveButton.classList.toggle("hidden");
        button.classList.toggle("bg-red-600");
        button.classList.toggle("bg-yellow-400");
        button.classList.toggle("hover:bg-yellow-600");
        button.classList.toggle("hover:bg-red-800");
        button.textContent = "edit";

        handleSave(row);
    };

    return Cell;
}

// Fungsi untuk menyimpan nilai asli sebelum diedit
function storeOriginalRow(row) {
    originalValues = [];
    const cells = row.querySelectorAll("td");
    for (let i = 1; i < cells.length - 3; i++) {
        // Lewatkan kolom pertama (No) dan terakhir (Aksi)
        originalValues.push(cells[i].innerText.trim());
    }
}

// Fungsi untuk mengembalikan nilai asli jika admin tidak jadi mengedit
function restoreOriginalRow(row) {
    const cells = row.querySelectorAll("td");
    for (let i = 1; i < cells.length - 3; i++) {
        // Lewatkan kolom pertama (No) dan terakhir (Aksi)
        cells[i].innerText = originalValues[i - 1]; // Kembalikan nilai asli dari array originalValues
    }
}

// Fungsi untuk membuat baris bisa diedit
function makeRowEditable(row) {
    const cells = row.querySelectorAll("td");
    for (let i = 2; i < cells.length - 3; i++) {
        const currentValue = cells[i].innerText.trim();

        // Lewatkan kolom pertama (No) dan terakhir (Aksi)
        if (i === 13) continue;
        if (i === 18) continue;
        if (i === 19) continue;
        if (i === 25) continue;
        if (i === 30) continue;
        if (i === 31) continue;

        if (i >= 9 && i <= 12) {
            cells[
                i
            ].innerHTML = `<input type="number" class="form-input w-full border border-black p-1" value="${covertInt(
                currentValue
            )}">`;
        } else if (i >= 14 && i <= 17) {
            cells[
                i
            ].innerHTML = `<input type="number" class="form-input w-full border border-black p-1" value="${covertInt(
                currentValue
            )}">`;
        } else if (i >= 21 && i <= 24) {
            cells[
                i
            ].innerHTML = `<input type="number" class="form-input w-full border border-black p-1" value="${covertInt(
                currentValue
            )}">`;
        } else if (i >= 26 && i <= 30) {
            cells[
                i
            ].innerHTML = `<input type="number" class="form-input w-full border border-black p-1" value="${covertInt(
                currentValue
            )}">`;
        } else {
            cells[
                i
            ].innerHTML = `<input type="text" class="form-input w-full border border-black p-1" value="${currentValue}">`;
        }
    }
}

function covertInt(row) {
    format = row;
    // Step 1: Remove "Rp" symbol
    let noSymbol = format.replace(/Rp\s?/, ""); // Removes "Rp" and any space after it

    // Step 2: Remove dots
    let noDots = noSymbol.replace(/\./g, "");

    // Step 3: Convert to integer
    let finalValue = parseInt(noDots);
    console.log(finalValue);

    return finalValue;
}

function handleSave(row) {
    const id = row.querySelector(".id").textContent; // Ambil ID dari elemen yang sesuai
    saveRow(row, id); // Panggil saveRow dengan ID
}

// Fungsi untuk menyimpan baris yang sudah diedit
async function saveRow(row, id) {
    const inputs = row.querySelectorAll("input");
    inputs.forEach((input) => {
        const td = input.closest("td");
        td.innerText = input.value;
    });
    row.querySelector(".editing-button").textContent = "edit";
    row.classList.remove("editable");
    row.querySelector(".save-button").classList.add("hidden");

    try {
        const res = await axios.post(`${baseUrl}/update-evaluasi/${user_id}`, {
            token: auth,
            reject: {
                status: "Pending",
            },
            permasalahan: {
                id: id,
                permasalahan: row
                    .querySelector(".permasalahan")
                    .textContent.trim(),
                sasaran: row.querySelector(".sasaran").textContent.trim(),
                indikator: row.querySelector(".indikator").textContent.trim(),
                target: row.querySelector(".target").textContent.trim(),
            },
            rencana_aksi: {
                permasalahan_id: id,
                rencana_aksi: row
                    .querySelector(".rencana-aksi")
                    .textContent.trim(),
                indikator: row
                    .querySelector(".rencanaAksi-indikator")
                    .textContent.trim(),
                satuan: row
                    .querySelector(".rencanaAksi-satuan")
                    .textContent.trim(),
                koordinator: row
                    .querySelector(".rencanaAksi-koordinator")
                    .textContent.trim(),
                pelaksana: row
                    .querySelector(".rencanaAksi-pelaksana")
                    .textContent.trim(),
            },
            target_penyelesaian: {
                twI: parseInt(
                    row
                        .querySelector(".target-penyelesaian-twI")
                        .textContent.trim()
                ),
                twII: parseInt(
                    row
                        .querySelector(".target-penyelesaian-twII")
                        .textContent.trim()
                ),
                twIII: parseInt(
                    row
                        .querySelector(".target-penyelesaian-twIII")
                        .textContent.trim()
                ),
                twIV: parseInt(
                    row
                        .querySelector(".target-penyelesaian-twIV")
                        .textContent.trim()
                ),
                type: row
                    .querySelector(".target-penyelesaian-type")
                    .textContent.trim(),
            },
            realisasi_penyelesaian: {
                twI: parseInt(
                    row
                        .querySelector(".realisasi-penyelesaian-twI")
                        .textContent.trim()
                ),
                twII: parseInt(
                    row
                        .querySelector(".realisasi-penyelesaian-twII")
                        .textContent.trim()
                ),
                twIII: parseInt(
                    row
                        .querySelector(".realisasi-penyelesaian-twIII")
                        .textContent.trim()
                ),
                twIV: parseInt(
                    row
                        .querySelector(".realisasi-penyelesaian-twIV")
                        .textContent.trim()
                ),
            },
            target_anggaran: {
                twI: covertInt(
                    row.querySelector(".target-anggaran-twI").textContent.trim()
                ),
                twII: covertInt(
                    row
                        .querySelector(".target-anggaran-twII")
                        .textContent.trim()
                ),
                twIII: covertInt(
                    row
                        .querySelector(".target-anggaran-twIII")
                        .textContent.trim()
                ),
                twIV: covertInt(
                    row
                        .querySelector(".target-anggaran-twIV")
                        .textContent.trim()
                ),
            },
            realisasi_anggaran: {
                twI: covertInt(
                    row
                        .querySelector(".realisasi-anggaran-twI")
                        .textContent.trim()
                ),
                twII: covertInt(
                    row
                        .querySelector(".realisasi-anggaran-twII")
                        .textContent.trim()
                ),
                twIII: covertInt(
                    row
                        .querySelector(".realisasi-anggaran-twIII")
                        .textContent.trim()
                ),
                twIV: covertInt(
                    row
                        .querySelector(".realisasi-anggaran-twIV")
                        .textContent.trim()
                ),
                jumlah: covertInt(
                    row
                        .querySelector(".realisasi-anggaran-jumlah")
                        .textContent.trim()
                ),
            },
        });

        console.log(res.data);
        if (res.status === 200) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
            });
            Toast.fire({
                icon: "success",
                title: "Update Data successfully",
            });
            loadData();
        } else if (res.status === 422) {
            Swal.fire({
                title: "Ups Error!",
                text: "Failed to save data! " + res.body.message,
                icon: "error",
            });
        }
    } catch (error) {
        console.log(error);

        // Periksa jika ada respons dari server
        if (error.response) {
            // Server merespons dengan status di luar rentang 2xx
            console.error("Response data:", error.response.data); // Data dari server
            console.error("Response status:", error.response.status); // Status kode
            console.error("Response headers:", error.response.headers); // Header respons
            Swal.fire({
                title: "Ups Error!",
                text: "Failed to save data! " + error.response.data.message,
                icon: "error",
            });
        } else if (error.request) {
            // Permintaan telah dibuat tetapi tidak ada respons yang diterima
            console.error("Request data:", error.request);
            Swal.fire({
                title: "Ups Error!",
                text: "Failed to save data! " + error.request,
                icon: "error",
            });
        } else {
            // Kesalahan lain
            console.error("Error message:", error.message);
        }
    }
}