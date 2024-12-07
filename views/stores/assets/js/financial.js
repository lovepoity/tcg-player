class StoreFinancial {
  constructor() {
    this.filterDate = document.querySelector('.store-financial__filter-date');
    this.revenueChart = null;

    if (!this.filterDate) {
      console.error('Filter date element not found');
      return;
    }

    this.initChart();
    this.bindEvents();
    this.loadData('month');
  }

  bindEvents() {
    this.filterDate.addEventListener('change', (e) => {
      this.loadData(e.target.value);
    });
  }

  async loadData(period) {
    try {
      const response = await fetch(`./actions/get_financial_data.php?period=${period}`);
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const data = await response.json();

      this.updateStats(data.stats);
      this.updateOrders(data.orders);
      this.updateEarnings(data.earnings);
      if (this.revenueChart) {
        this.updateChart(data.chartData);
      }

    } catch (error) {
      console.error('Error loading financial data:', error);
    }
  }

  updateStats(stats) {
    const elements = {
      revenue: document.querySelector('[data-stat="revenue"]'),
      orders: document.querySelector('[data-stat="orders"]'),
      earnings: document.querySelector('[data-stat="earnings"]'),
      commission: document.querySelector('[data-stat="commission"]')
    };

    if (elements.revenue) elements.revenue.textContent = this.formatCurrency(stats.revenue);
    if (elements.orders) elements.orders.textContent = stats.totalOrders;
    if (elements.earnings) elements.earnings.textContent = this.formatCurrency(stats.earnings);
    if (elements.commission) elements.commission.textContent = this.formatCurrency(stats.commission);
  }

  updateOrders(orders) {
    const tbody = document.querySelector('.store-financial__recent-orders tbody');
    if (!tbody) return;

    tbody.innerHTML = orders.map(order => `
      <tr>
        <td>#${String(order.id).padStart(5, '0')}</td>
        <td>${this.formatDate(order.date)}</td>
        <td>${order.items}</td>
        <td>${this.formatCurrency(order.total)}</td>
        <td>${order.status}</td>
      </tr>
    `).join('');
  }

  updateEarnings(earnings) {
    const tbody = document.querySelector('.store-financial__earnings tbody');
    if (!tbody) return;

    tbody.innerHTML = earnings.map(earning => `
      <tr>
        <td>#${String(earning.order_id).padStart(5, '0')}</td>
        <td>${this.formatDate(earning.date)}</td>
        <td>${this.formatCurrency(earning.amount)}</td>
        <td>${this.formatCurrency(earning.commission)}</td>
      </tr>
    `).join('');
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
    if (!this.revenueChart) return;

    this.revenueChart.data.labels = data.labels;
    this.revenueChart.data.datasets[0].data = data.values;
    this.revenueChart.update();
  }

  formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD'
    }).format(amount);
  }

  formatDate(date) {
    const d = new Date(date);
    const day = d.getDate();
    const month = d.toLocaleString('en-GB', { month: 'short' });
    const year = d.getFullYear();
    return `${day} ${month}, ${year}`;
  }
}
