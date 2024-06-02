document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('electionChart').getContext('2d');
    const electionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['WWW', 'XXX', 'YYY', 'ZZZ'],
            datasets: [{
                label: 'Votes',
                data: [0, 0, 0, 0], // This should be dynamically populated from the database
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Fetch the data from the server and update the chart
    fetch('get_chart_data.php')
        .then(response => response.json())
        .then(data => {
            electionChart.data.datasets[0].data = data;
            electionChart.update();
        });
});
