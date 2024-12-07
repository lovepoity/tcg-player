class StoreDashboard {
  constructor() {
    this.revenueChart = null;
    this.filterDate = document.querySelector('.store-dashboard__filter-date');
    this.init();
  }

  init() {
    this.initChart();
    this.bindEvents();
    this.loadDashboardData('month'); // Default period
  }

  bindEvents() {
    if (this.filterDate) {
      this.filterDate.addEventListener('change', (e) => {
        this.loadDashboardData(e.target.value);
      });
    }
  }

  async loadDashboardData(period) {
    try {
      const response = await fetch(`./actions/get_dashboard_data.php?period=${period}`);
      const data = await response.json();

      if (!data || typeof data !== 'object') {
        throw new Error('Invalid data format received');
      }

      this.updateStats(data.stats);
      this.updateTopExpensiveCards(data.topExpensiveCards);
      this.updateTopQuantityCards(data.topQuantityCards);
      this.updateTopSellingCards(data.topSellingCards);
      this.updateTopCustomers(data.topCustomers);
      this.updateChart(data.chartData);
    } catch (error) {
      console.error('Error:', error);
      showToast('Error loading dashboard data: ' + error.message);
    }
  }

  updateStats(stats) {
    try {
      document.querySelector('[data-stat="games"]').textContent = stats.games;
      document.querySelector('[data-stat="sets"]').textContent = stats.sets;
      document.querySelector('[data-stat="cards"]').textContent = stats.cards;
      document.querySelector('[data-stat="orders"]').textContent = stats.orders;
      document.querySelector('[data-stat="earnings"]').textContent =
        `$${parseFloat(stats.earnings).toFixed(2)}`;
    } catch (error) {
      console.error('Error updating stats:', error);
    }
  }

  updateTopExpensiveCards(cards) {
    try {
      const tbody = document.querySelector('.store-dashboard__top-expensive tbody');
      if (!tbody) throw new Error('Top expensive cards tbody not found');

      tbody.innerHTML = cards.map(card => `
        <tr>
          <td>${card.card_name}</td>
          <td>${card.game_name}</td>
          <td>${card.set_name}</td>
          <td>$${parseFloat(card.price).toFixed(2)}</td>
          <td>${card.quantity}</td>
        </tr>
      `).join('');
    } catch (error) {
      console.error('Error updating top expensive cards:', error);
    }
  }

  updateTopQuantityCards(cards) {
    try {
      const tbody = document.querySelector('.store-dashboard__top-quantity tbody');
      if (!tbody) throw new Error('Top quantity cards tbody not found');

      tbody.innerHTML = cards.map(card => `
        <tr>
          <td>${card.card_name}</td>
          <td>${card.game_name}</td>
          <td>${card.set_name}</td>
          <td>$${parseFloat(card.price).toFixed(2)}</td>
          <td>${card.quantity}</td>
        </tr>
      `).join('');
    } catch (error) {
      console.error('Error updating top quantity cards:', error);
    }
  }

  updateTopSellingCards(cards) {
    try {
      const tbody = document.querySelector('.store-dashboard__top-selling tbody');
      if (!tbody) throw new Error('Top selling cards tbody not found');

      tbody.innerHTML = cards.map(card => `
        <tr>
          <td>${card.card_name}</td>
          <td>${card.game_name}</td>
          <td>${card.set_name}</td>
          <td>$${parseFloat(card.price).toFixed(2)}</td>
          <td>${card.total_sold}</td>
        </tr>
      `).join('');
    } catch (error) {
      console.error('Error updating top selling cards:', error);
    }
  }

  updateTopCustomers(customers) {
    try {
      const tbody = document.querySelector('.store-dashboard__top-customers tbody');
      if (!tbody) throw new Error('Top customers tbody not found');

      tbody.innerHTML = customers.map(customer => `
        <tr>
          <td>${customer.customer_name}</td>
          <td>${customer.total_orders}</td>
          <td>${customer.total_items}</td>
          <td>$${parseFloat(customer.total_spent).toFixed(2)}</td>
        </tr>
      `).join('');
    } catch (error) {
      console.error('Error updating top customers:', error);
    }
  }

  initChart() {
    const canvas = document.getElementById('revenueChart');
    if (!canvas) {
      console.error('Canvas element not found');
      return;
    }

    const ctx = canvas.getContext('2d');
    this.revenueChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [],
        datasets: [{
          label: 'Revenue',
          data: [],
          borderColor: '#3b82f6',
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  }

  updateChart(data) {
    if (!this.revenueChart || !data || !data.labels || !data.values) {
      console.error('Invalid chart data');
      return;
    }

    this.revenueChart.data.labels = data.labels;
    this.revenueChart.data.datasets[0].data = data.values;
    this.revenueChart.update();
  }
}
