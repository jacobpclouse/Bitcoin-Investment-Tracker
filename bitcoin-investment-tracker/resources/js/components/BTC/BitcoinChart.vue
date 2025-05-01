<template>
    <div>
      <h2 class="text-xl font-bold mb-2">Bitcoin Price Chart</h2>
  
      <div class="mb-4">
        <label for="period" class="mr-2 font-semibold">Select Time Period:</label>
        <select v-model="selectedPeriod" @change="fetchData" id="period" class="p-1 border rounded">
          <option value="1">1 Day</option>
          <option value="7">7 Days</option>
          <option value="30">30 Days</option>
          <option value="90">90 Days</option>
          <option value="180">6 Months</option>
          <option value="365">1 Year</option>
        </select>
      </div>
  
      <canvas ref="chartCanvas"></canvas>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import Chart from 'chart.js/auto';
  
  const selectedPeriod = ref("7");
  const chartCanvas = ref(null);
  let chartInstance = null;
  
  const fetchData = async () => {
    const res = await fetch(`/bitcoin-data?days=${selectedPeriod.value}`);
    const json = await res.json();
  
    const prices = json.prices;
    const labels = prices.map(p => new Date(p[0]).toLocaleDateString());
    const data = prices.map(p => p[1]);
  
    if (chartInstance) {
      chartInstance.destroy();
    }
  
    const ctx = chartCanvas.value.getContext('2d');
    chartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'BTC Price (USD)',
          data,
          fill: true,
          backgroundColor: 'rgba(255, 206, 86, 0.2)',
          borderColor: 'rgba(255, 206, 86, 1)',
          tension: 0.3,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          tooltip: {
            mode: 'index',
            intersect: false
          }
        },
        scales: {
          y: {
            beginAtZero: false
          }
        }
      }
    });
  };
  
  onMounted(fetchData);
  </script>
  

<!-- <template>
    <div>
      <h2 class="text-xl font-bold mb-2">Bitcoin Price Chart</h2>
      <canvas id="bitcoinChart"></canvas>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import Chart from 'chart.js/auto';
  
  const chartRef = ref(null);
  
  onMounted(async () => {
    const res = await fetch('/bitcoin-data');
    const data = await res.json();
    const price = data.bitcoin.usd;
  
    const ctx = document.getElementById('bitcoinChart').getContext('2d');
  
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: [new Date().toLocaleTimeString()],
        datasets: [{
          label: 'BTC Price (USD)',
          data: [price],
          backgroundColor: 'rgba(255, 206, 86, 0.2)',
          borderColor: 'rgba(255, 206, 86, 1)',
          borderWidth: 1,
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: false
          }
        }
      }
    });
  });
  </script>
   -->