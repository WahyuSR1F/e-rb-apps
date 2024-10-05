if (typeof baseUrl === 'undefined') {
    var baseUrl = "http://127.0.0.1:8010/api";
}

let myBarChart;
let myProblemChart;
let myRenaksiChart;
let idCluster;
let idTema;
let idPermasalahan;
let idRenaksi;



Chart.register(ChartDataLabels);

const chartConfig = {
    plugins: {
        datalabels: {
            color: '#fff',
            font: {
                weight: 'bold'
            },
            formatter: (value) => value + '%'
        },
        legend: {
            display: true,
            position: 'top',
            labels: {
                font: {
                    size: 14
                }
            }
        }
    },
    scales: {
        x: {
            grid: {
                display: false
            }
        },
        y: {
            beginAtZero: true,
            max: 100,
            ticks: {
                callback: function (value) {
                    return value + '%';
                }
            }
        }
    },
    responsive: true,
    maintainAspectRatio: false
};

const chartConfig2 = {
    ...chartConfig,
    plugins: {
        datalabels: {
            formatter: (value) => value
        }
    },
    indexAxis: 'x',
    scales: {
        x: {
            stacked: false,
            grid: {
                display: false
            },
            categoryPercentage: 0.7
        },
        y: {
            stacked: false,
            beginAtZero: true,
            ticks: {
                callback: function (value) {
                    return value;
                }
            }
        }
    }
};

function createGradient(ctx, colorStart, colorEnd) {
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, colorStart);
    gradient.addColorStop(1, colorEnd);
    return gradient;
}

async function loadClusters() {
    try {
        const response = await axios.get(`${baseUrl}/get-cluster`,{withCredentials: true, params:{token:auth}});
        const clusters = response.data.data;
        const selectElement = document.getElementById('cluster-select');
        selectElement.innerHTML = '<option value="">Pilih Cluster</option>';
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

async function loadTema(clusterId, year, startDate, endDate) {
    try {
        const params = {
            token: auth,
            id: clusterId,
            years: year,
            dateAwal: startDate,
            dateAkhir: endDate
        };
        const response = await axios.get(`${baseUrl}/get-tema`, {withCredentials: true, params });
        const temas = response.data.data;
        const selectElement = document.getElementById('theme-select');
        selectElement.innerHTML = '<option value="">Pilih Tema</option>';
        temas.forEach(tema => {
            const option = document.createElement('option');
            option.value = tema.id;
            option.textContent = tema.nama;
            selectElement.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading tema:', error);
    }
}

async function loadPermasalahan(temaId, year, startDate, endDate) {
    try {
        const params = {
            token:auth,
            id: temaId,
            years: year,
            dateAwal: startDate,
            dateAkhir: endDate
        };
        const response = await axios.get(`${baseUrl}/get-permasalahan-by-tema`, { withCredentials: true, params });
        const permasalahans = response.data.data;
        const selectElement = document.getElementById('problem-select');
        selectElement.innerHTML = '<option value="">Pilih Permasalahan</option>';
        permasalahans.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.unique_namespace + ' ' + item.permasalahan;
            selectElement.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading permasalahan:', error);
    }
}

async function loadRenaksi(permasalahanId, year, startDate, endDate) {
    try {
        const params = {
            token:auth,
            id: permasalahanId,
            years: year,
            dateAwal: startDate,
            dateAkhir: endDate
        };
        const response = await axios.get(`${baseUrl}/get-rencana-aksi-permasalahan`, { withCredentials: true, params });
        const renaksi = response.data.data;
        const selectElement = document.getElementById('renaksi-select');
        selectElement.innerHTML = '<option value="">Pilih Renaksi</option>';
        renaksi.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.unique_namespace + ' ' + item.rencana_aksi;
            selectElement.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading renaksi:', error);
    }
}

document.addEventListener('DOMContentLoaded', function(){
    auth = document.getElementById('auth_token').value; 
    fetchData('2024');
    loadClusters(auth)
});

document.getElementById('cluster-select').addEventListener('change', function () {
    idCluster = this.value;
    var themeDropdown = document.getElementById('theme-dropdown');
    var dropdownContainer = document.getElementById('dropdown-container');
    var themeChartContainer = document.getElementById('theme-chart-container');
    var themeChartContainer2 = document.getElementById('theme-chart-container2');

    if (idCluster) {
        themeDropdown.classList.remove('hidden');
        const selectedYear = document.getElementById('selectTahun').value;
        const startDate = document.getElementById('periodeAwal').value;
        const endDate = document.getElementById('periodeAkhir').value;
        loadTema(idCluster, selectedYear, startDate, endDate);
    } else {
        themeDropdown.classList.add('hidden');
        themeChartContainer.classList.add('hidden');
        themeChartContainer2.classList.add('hidden');
        dropdownContainer.classList.add('hidden');
        document.getElementById('problem-chart').classList.add('hidden');
        if (myBarChart) myBarChart.destroy();
        if (myProblemChart) myProblemChart.destroy();
        if (myRenaksiChart) myRenaksiChart.destroy();
    }
});

document.getElementById('theme-select').addEventListener('change', async function () {
    idTema = this.value;
    var themeChartContainer = document.getElementById('theme-chart-container');
    var themeChartContainer2 = document.getElementById('theme-chart-container2');
    var dropdownContainer = document.getElementById('dropdown-container');
    var ctx = document.getElementById('myChart').getContext('2d');
    var ctx2 = document.getElementById('myChart2').getContext('2d');

    if (idTema) {
        themeChartContainer.classList.remove('hidden');
        themeChartContainer2.classList.remove('hidden');
        dropdownContainer.classList.remove('hidden');

        const selectedYear = document.getElementById('selectTahun').value;
        const startDate = document.getElementById('periodeAwal').value;
        const endDate = document.getElementById('periodeAkhir').value;

        loadPermasalahan(idTema, selectedYear, startDate, endDate);

        const fetchData = await rataRata(idTema, selectedYear, startDate, endDate);
        const fetchDataAnggaran = await rataRataAnggaran(idTema, selectedYear, startDate, endDate);

        const labels = fetchData.map(item => item.permasalahan);
        const dataValues = fetchData.map(item => parseFloat(item.rata_rata_capaian).toFixed(1));

        const labelsAnggaran = fetchDataAnggaran.map(item => item.permasalahan);
        const dataValuesAnggaran = fetchDataAnggaran.map(item => parseFloat(item.rata_rata_capaian).toFixed(1));

        var data = {
            labels: labels,
            datasets: [{
                label: `Penyelesaian Report`,
                data: dataValues,
                backgroundColor: createGradient(ctx, 'rgba(54, 162, 235, 0.8)', 'rgba(54, 162, 235, 0.2)'),
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        var data1 = {
            labels: labelsAnggaran,
            datasets: [{
                label: `Anggaran Report`,
                data: dataValuesAnggaran,
                backgroundColor: createGradient(ctx2, 'rgba(255, 99, 132, 0.8)', 'rgba(255, 99, 132, 0.2)'),
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };

        var config = {
            type: 'bar',
            data: data,
            options: chartConfig
        };

        var config1 = {
            type: 'bar',
            data: data1,
            options: chartConfig
        };

        if (window.myBarChart || window.myBarChart2) {
            window.myBarChart.data = data;
            window.myBarChart.update();
            window.myBarChart2.data = data1;
            window.myBarChart2.update();
        } else {
            window.myBarChart = new Chart(ctx, config);
            window.myBarChart2 = new Chart(ctx2, config1);
        }
    } else {
        themeChartContainer.classList.add('hidden');
        themeChartContainer2.classList.add('hidden');
        dropdownContainer.classList.add('hidden');
        document.getElementById('problem-chart').classList.add('hidden');
        if (window.myBarChart) window.myBarChart.destroy();
        if (window.myBarChart2) window.myBarChart2.destroy();
    }
});

document.getElementById('problem-select').addEventListener('change', async function () {
    idPermasalahan = this.value;
    var renaksiDropdown = document.getElementById('renaksi-dropdown');
    var problemChart = document.getElementById('problem-chart');
    var renaksiChartContainer = document.getElementById('renaksi-chart-container');
    var ctx = document.getElementById('myChartProblem').getContext('2d');
    var ctx2 = document.getElementById('myChartProblem2').getContext('2d');

    if (idPermasalahan) {
        renaksiDropdown.classList.remove('hidden');
        problemChart.classList.remove('hidden');
        renaksiChartContainer.classList.add('hidden');

        const selectedYear = document.getElementById('selectTahun').value;
        const startDate = document.getElementById('periodeAwal').value;
        const endDate = document.getElementById('periodeAkhir').value;

        loadRenaksi(idPermasalahan, selectedYear, startDate, endDate);

        try {
            const fetchDataRenaksiPenyelesaian = await capaianRenaksiPenyelesaian(idPermasalahan, selectedYear, startDate, endDate);
            const fetchDataRenaksiAnggaran = await capaianRenaksiAnggaran(idPermasalahan, selectedYear, startDate, endDate);

            const labels = fetchDataRenaksiPenyelesaian.map(item => item.renaksi);
            const dataValues = fetchDataRenaksiPenyelesaian.map(item => parseFloat(item.capaian).toFixed(1));

            const labelsAnggaran = fetchDataRenaksiAnggaran.map(item => item.renaksi);
            const dataValuesAnggaran = fetchDataRenaksiAnggaran.map(item => parseFloat(item.capaian).toFixed(1));

            var data = {
                labels: labels,
                datasets: [{
                    label: `Renaksi Penyelesaian`,
                    data: dataValues,
                    backgroundColor: createGradient(ctx, 'rgba(255, 99, 132, 0.8)', 'rgba(255, 99, 132, 0.2)'),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            };

            var data1 = {
                labels: labelsAnggaran,
                datasets: [{
                    label: `Renaksi Anggaran`,
                    data: dataValuesAnggaran,
                    backgroundColor: createGradient(ctx2, 'rgba(255, 99, 132, 0.8)', 'rgba(255, 99, 132, 0.2)'),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            };

            var config = {
                type: 'bar',
                data: data,
                options: chartConfig
            };

            var config2 = {
                type: 'bar',
                data: data1,
                options: chartConfig
            };

            if (window.myProblemChart || window.myProblemChart2) {
                window.myProblemChart.data = data;
                window.myProblemChart.update();
                window.myProblemChart2.data = data1;
                window.myProblemChart2.update();
            } else {
                window.myProblemChart = new Chart(ctx, config);
                window.myProblemChart2 = new Chart(ctx2, config2);
            }
        } catch (error) {
            console.error('Error fetching renaksi data:', error);
        }
    } else {
        problemChart.classList.add('hidden');
        renaksiDropdown.classList.add('hidden');
        renaksiChartContainer.classList.add('hidden');
        if (myProblemChart) myProblemChart.destroy();
        if (myRenaksiChart) myRenaksiChart.destroy();
    }
});

document.getElementById('renaksi-select').addEventListener('change', async function () {
    idRenaksi = this.value;
    var renaksiChartContainer = document.getElementById('renaksi-chart-container');
    var ctx = document.getElementById('myChartRenaksi').getContext('2d');

    if (idRenaksi) {
        renaksiChartContainer.classList.remove('hidden');

        const selectedYear = document.getElementById('selectTahun').value;
        const startDate = document.getElementById('periodeAwal').value;
        const endDate = document.getElementById('periodeAkhir').value;

        const fetchDataTWPenyelesaian = await TWPenyelesaian(idRenaksi, selectedYear, startDate, endDate);

        const labels = fetchDataTWPenyelesaian.map(item => item.triwulan);
        const dataTarget = fetchDataTWPenyelesaian.map(item => item.target);
        const dataCapaian = fetchDataTWPenyelesaian.map(item => item.capaian);

        var data = {
            labels: labels,
            datasets: [{
                label: `Target`,
                data: dataTarget,
                backgroundColor: createGradient(ctx, 'rgba(75, 192, 192, 0.8)', 'rgba(75, 192, 192, 0.2)'),
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            },
            {
                label: `Anggaran`,
                data: dataCapaian,
                backgroundColor: createGradient(ctx, 'rgba(255, 99, 132, 0.8)', 'rgba(255, 99, 132, 0.2)'),
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };

        var config = {
            type: 'bar',
            data: data,
            options: chartConfig2
        };

        if (window.myRenaksiChart) {
            window.myRenaksiChart.data = data;
            window.myRenaksiChart.update();
        } else {
            window.myRenaksiChart = new Chart(ctx, config);
        }
    } else {
        renaksiChartContainer.classList.add('hidden');
        if (myRenaksiChart) myRenaksiChart.destroy();
    }
});

async function rataRata(id, year, startDate, endDate) {
    const params = {
        token:auth,
        id: id,
        years: year,
        dateAwal: startDate,
        dateAkhir: endDate
    };
    const response = await axios.get(`${baseUrl}/get-capaian-permasalahan`, { withCredentials: true, params });
    return response.data.data;
}

async function rataRataAnggaran(id, year, startDate, endDate) {
    const params = {
        token:auth,
        id: id,
        years: year,
        dateAwal: startDate,
        dateAkhir: endDate
    };
    const response = await axios.get(`${baseUrl}/get-capaian-anggaran`, {withCredentials: true, params });
    return response.data.data;
}

async function capaianRenaksiPenyelesaian(id, year, startDate, endDate) {
    const params = {
        token:auth,
        id: id,
        years: year,
        dateAwal: startDate,
        dateAkhir: endDate
    };
    const response = await axios.get(`${baseUrl}/get-capaian-renaksi-penyelesaian`, {withCredentials: true, params });
    return response.data.data;
}

async function capaianRenaksiAnggaran(id, year, startDate, endDate) {
    const params = {
        token:auth,
        id: id,
        years: year,
        dateAwal: startDate,
        dateAkhir: endDate
    };
    const response = await axios.get(`${baseUrl}/get-capaian-renaksi-anggaran`, { withCredentials: true, params });
    return response.data.data;
}

async function TWPenyelesaian(id, year, startDate, endDate) {
    const params = {
        token:auth,
        id: id,
        years: year,
        dateAwal: startDate,
        dateAkhir: endDate
    };
    const response = await axios.get(`${baseUrl}/get-capaian-tw-penyelesaian`, {withCredentials: true, params });
    return response.data.data;
}

async function fetchData( year, startDate, endDate) {
    try {
        const params = {
            token: auth,
            years: year,
            dateAwal: startDate,
            dateAkhir: endDate
        };
        const response = await axios.get(`${baseUrl}/get-count`, { withCredentials: true,
             params });
        const counts = response.data.data;

        document.getElementById('pemasalahan-count').innerText = counts.permasalahan || 0;
        document.getElementById('rencana-aksi-count').innerText = counts.renaksi || 0;
        document.getElementById('penyelesaian-count').innerText = counts.penyelesaian || 0;
        document.getElementById('anggaran-count').innerText = counts.anggaran || 0;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}




async function updateAllCharts() {
    const selectedYear = document.getElementById('selectTahun').value;
    const startDate = document.getElementById('periodeAwal').value;
    const endDate = document.getElementById('periodeAkhir').value;

    await fetchData(selectedYear, startDate, endDate);

    if (idCluster) {
        await loadTema(idCluster, selectedYear, startDate, endDate);
    }

    if (idTema) {
        const fetchData = await rataRata(idTema, selectedYear, startDate, endDate);
        const fetchDataAnggaran = await rataRataAnggaran(idTema, selectedYear, startDate, endDate);

        // Update myBarChart and myBarChart2 with new data
        if (window.myBarChart && window.myBarChart2) {
            window.myBarChart.data.labels = fetchData.map(item => item.permasalahan);
            window.myBarChart.data.datasets[0].data = fetchData.map(item => parseFloat(item.rata_rata_capaian).toFixed(1));
            window.myBarChart.update();

            window.myBarChart2.data.labels = fetchDataAnggaran.map(item => item.permasalahan);
            window.myBarChart2.data.datasets[0].data = fetchDataAnggaran.map(item => parseFloat(item.rata_rata_capaian).toFixed(1));
            window.myBarChart2.update();
        }
    }

    if (idPermasalahan) {
        const fetchDataRenaksiPenyelesaian = await capaianRenaksiPenyelesaian(idPermasalahan, selectedYear, startDate, endDate);
        const fetchDataRenaksiAnggaran = await capaianRenaksiAnggaran(idPermasalahan, selectedYear, startDate, endDate);

        // Update myProblemChart and myProblemChart2 with new data
        if (window.myProblemChart && window.myProblemChart2) {
            window.myProblemChart.data.labels = fetchDataRenaksiPenyelesaian.map(item => item.renaksi);
            window.myProblemChart.data.datasets[0].data = fetchDataRenaksiPenyelesaian.map(item => parseFloat(item.capaian).toFixed(1));
            window.myProblemChart.update();

            window.myProblemChart2.data.labels = fetchDataRenaksiAnggaran.map(item => item.renaksi);
            window.myProblemChart2.data.datasets[0].data = fetchDataRenaksiAnggaran.map(item => parseFloat(item.capaian).toFixed(1));
            window.myProblemChart2.update();
        }
    }

    if (idRenaksi) {
        const fetchDataTWPenyelesaian = await TWPenyelesaian(idRenaksi, selectedYear, startDate, endDate);

        // Update myRenaksiChart with new data
        if (window.myRenaksiChart) {
            window.myRenaksiChart.data.labels = fetchDataTWPenyelesaian.map(item => item.triwulan);
            window.myRenaksiChart.data.datasets[0].data = fetchDataTWPenyelesaian.map(item => item.target);
            window.myRenaksiChart.data.datasets[1].data = fetchDataTWPenyelesaian.map(item => item.capaian);
            window.myRenaksiChart.update();
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    
    const periodeAwal = document.getElementById('periodeAwal');
    const periodeAkhir = document.getElementById('periodeAkhir');
    const selectTahun = document.getElementById('selectTahun');
    const clearPeriodeButton = document.getElementById('clearPeriode');
    const submitButton = document.getElementById('submitButton');

    flatpickr(periodeAwal, {});
    flatpickr(periodeAkhir, {});

    selectTahun.addEventListener('change', function () {
        const selectedYear = selectTahun.value;

        if (selectedYear) {
            flatpickr(periodeAwal, {
                dateFormat: 'Y-m-d',
                minDate: `${selectedYear}-01-01`,
                maxDate: `${selectedYear}-12-31`
            });

            flatpickr(periodeAkhir, {
                dateFormat: 'Y-m-d',
                minDate: `${selectedYear}-01-01`,
                maxDate: `${selectedYear}-12-31`
            });
        } else {
            flatpickr(periodeAwal, {});
            flatpickr(periodeAkhir, {});
        }
    });

    clearPeriodeButton.addEventListener('click', function () {
        periodeAwal.value = '';
        periodeAkhir.value = '';
    });

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    submitButton.addEventListener('click', function () {
        let tahun = selectTahun.value;
        let awal = periodeAwal.value;
        let akhir = periodeAkhir.value;
        if (awal && akhir) {
            awal = formatDate(awal);
            akhir = formatDate(akhir);
        }
        fetchData(tahun, awal, akhir);
        updateAllCharts();
    });
});