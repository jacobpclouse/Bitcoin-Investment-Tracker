<template>
    <div>
      <h2 class="text-xl font-bold mb-4">Investment Comparison Tracker</h2>
  
      <div class="mb-6 space-y-4">
        <div v-if="errorMessage" class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded">
          {{ errorMessage }}
        </div>
        <div class="flex gap-4 flex-wrap items-end">
          <div>
            <label for="investmentAmount" class="block font-semibold mb-1">Initial Investment ($):</label>
            <input 
              v-model.number="investmentAmount" 
              type="number" 
              id="investmentAmount"
              min="1"
              step="0.01"
              placeholder="e.g., 1000"
              class="p-2 border rounded"
            />
          </div>
          
          <div>
            <label for="startDate" class="block font-semibold mb-1">Start Date:</label>
            <input 
              v-model="startDate" 
              type="date" 
              id="startDate"
              class="p-2 border rounded"
            />
          </div>
          <button 
            @click="handleSubmit"
            :disabled="isLoading || !investmentAmount || !startDate"
            class="px-6 py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition"
          >
            {{ isLoading ? 'Loading...' : 'Submit' }}
          </button>
          <div v-if="startDate" class="text-sm text-gray-600 dark:text-gray-400">
            <span>Period: {{ calculatedPeriod }} days</span>
          </div>
        </div>

        <div class="flex gap-4 flex-wrap items-center">
          <span class="font-semibold">Investment Vehicles:</span>
          <label class="flex items-center gap-2">
            <input v-model="selectedVehicles" type="checkbox" value="bitcoin" class="w-4 h-4" />
            <span>Bitcoin</span>
          </label>
          <label class="flex items-center gap-2">
            <input v-model="selectedVehicles" type="checkbox" value="ethereum" class="w-4 h-4" />
            <span>Ethereum</span>
          </label>
          <label class="flex items-center gap-2">
            <input v-model="selectedVehicles" type="checkbox" value="sp500" class="w-4 h-4" />
            <span>S&P 500</span>
          </label>
          <label class="flex items-center gap-2">
            <input v-model="selectedVehicles" type="checkbox" value="gold" class="w-4 h-4" />
            <span>Gold</span>
          </label>
          <label class="flex items-center gap-2">
            <input v-model="selectedVehicles" type="checkbox" value="bonds" class="w-4 h-4" />
            <span>Bonds (4% Annual)</span>
          </label>
          <label class="flex items-center gap-2">
            <input v-model="selectedVehicles" type="checkbox" value="reit" class="w-4 h-4" />
            <span>Real Estate (REIT)</span>
          </label>
          <label class="flex items-center gap-2">
            <input v-model="selectedVehicles" type="checkbox" value="inflation" class="w-4 h-4" />
            <span>Inflation (2% Annual)</span>
          </label>
        </div>

        <div v-if="investmentAmount && startDate" class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
          <div class="grid grid-cols-1 md:grid-cols-8 gap-3 text-sm">
            <div>
              <p class="text-xs text-gray-600 dark:text-gray-400">Initial Value</p>
              <p class="font-bold">${{ investmentAmount.toFixed(2) }}</p>
            </div>
            <div v-if="selectedVehicles.includes('bitcoin')" class="border-l-2 border-yellow-500 pl-2">
              <p class="text-xs text-gray-600 dark:text-gray-400">Bitcoin</p>
              <p class="font-bold text-yellow-600">${{ values.bitcoin.toFixed(2) }}</p>
              <p class="text-xs" :class="returns.bitcoin >= 0 ? 'text-green-600' : 'text-red-600'">
                {{ returns.bitcoin.toFixed(1) }}%
              </p>
            </div>
            <div v-if="selectedVehicles.includes('ethereum')" class="border-l-2 border-purple-500 pl-2">
              <p class="text-xs text-gray-600 dark:text-gray-400">Ethereum</p>
              <p class="font-bold text-purple-600">${{ values.ethereum.toFixed(2) }}</p>
              <p class="text-xs" :class="returns.ethereum >= 0 ? 'text-green-600' : 'text-red-600'">
                {{ returns.ethereum.toFixed(1) }}%
              </p>
            </div>
            <div v-if="selectedVehicles.includes('sp500')" class="border-l-2 border-blue-500 pl-2">
              <p class="text-xs text-gray-600 dark:text-gray-400">S&P 500</p>
              <p class="font-bold text-blue-600">${{ values.sp500.toFixed(2) }}</p>
              <p class="text-xs" :class="returns.sp500 >= 0 ? 'text-green-600' : 'text-red-600'">
                {{ returns.sp500.toFixed(1) }}%
              </p>
            </div>
            <div v-if="selectedVehicles.includes('gold')" class="border-l-2 border-yellow-400 pl-2">
              <p class="text-xs text-gray-600 dark:text-gray-400">Gold</p>
              <p class="font-bold text-yellow-400">${{ values.gold.toFixed(2) }}</p>
              <p class="text-xs" :class="returns.gold >= 0 ? 'text-green-600' : 'text-red-600'">
                {{ returns.gold.toFixed(1) }}%
              </p>
            </div>
            <div v-if="selectedVehicles.includes('bonds')" class="border-l-2 border-gray-500 pl-2">
              <p class="text-xs text-gray-600 dark:text-gray-400">Bonds</p>
              <p class="font-bold text-gray-600">${{ values.bonds.toFixed(2) }}</p>
              <p class="text-xs text-green-600">4.0%</p>
            </div>
            <div v-if="selectedVehicles.includes('reit')" class="border-l-2 border-orange-500 pl-2">
              <p class="text-xs text-gray-600 dark:text-gray-400">REIT</p>
              <p class="font-bold text-orange-600">${{ values.reit.toFixed(2) }}</p>
              <p class="text-xs" :class="returns.reit >= 0 ? 'text-green-600' : 'text-red-600'">
                {{ returns.reit.toFixed(1) }}%
              </p>
            </div>
            <div v-if="selectedVehicles.includes('inflation')" class="border-l-2 border-orange-300 pl-2">
              <p class="text-xs text-gray-600 dark:text-gray-400">Inflation</p>
              <p class="font-bold text-orange-400">${{ values.inflation.toFixed(2) }}</p>
              <p class="text-xs text-green-600">2.0%</p>
            </div>
          </div>
        </div>
      </div>
  
      <div v-if="!chartData" class="flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 p-16 min-h-96">
        <div class="text-center">
          <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Investment Chart</h3>
          <p class="text-gray-500 dark:text-gray-400">Select an investment amount and start date, then click Submit to view the investment comparison chart.</p>
        </div>
      </div>
      <canvas v-else ref="chartCanvas"></canvas>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import Chart from 'chart.js/auto';
  
  const chartCanvas = ref(null);
  let chartInstance = null;
  const dataCache = ref({});
  const isLoading = ref(false);
  const errorMessage = ref('');
  const chartData = ref(null);
  const investmentAmount = ref(1000);
  const startDate = ref('');
  const calculatedPeriod = ref(null);
  const selectedVehicles = ref(['bitcoin', 'sp500']);
  
  const values = ref({
    bitcoin: 0,
    ethereum: 0,
    sp500: 0,
    gold: 0,
    bonds: 0,
    reit: 0,
    inflation: 0,
  });
  
  const returns = computed(() => ({
    bitcoin: investmentAmount.value > 0 ? ((values.value.bitcoin - investmentAmount.value) / investmentAmount.value) * 100 : 0,
    ethereum: investmentAmount.value > 0 ? ((values.value.ethereum - investmentAmount.value) / investmentAmount.value) * 100 : 0,
    sp500: investmentAmount.value > 0 ? ((values.value.sp500 - investmentAmount.value) / investmentAmount.value) * 100 : 0,
    gold: investmentAmount.value > 0 ? ((values.value.gold - investmentAmount.value) / investmentAmount.value) * 100 : 0,
    bonds: investmentAmount.value > 0 ? ((values.value.bonds - investmentAmount.value) / investmentAmount.value) * 100 : 0,
    reit: investmentAmount.value > 0 ? ((values.value.reit - investmentAmount.value) / investmentAmount.value) * 100 : 0,
    inflation: investmentAmount.value > 0 ? ((values.value.inflation - investmentAmount.value) / investmentAmount.value) * 100 : 0,
  }));
  
  const handleStartDateChange = () => {
    calculatePeriod();
    // Don't fetch data, just calculate the period
  };
  
  const handleSubmit = () => {
    calculatePeriod();
    fetchData();
  };
  
  const calculatePeriod = () => {
    if (!startDate.value) {
      calculatedPeriod.value = null;
      return;
    }
    
    const start = new Date(startDate.value);
    const end = new Date();
    const diffTime = Math.abs(end - start);
    const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    calculatedPeriod.value = Math.max(1, days);
  };
  
  const fetchData = async () => {
    if (!investmentAmount.value || !startDate.value) {
      return;
    }
    
    const period = calculatedPeriod.value || 7;
    
    // Return cached data if available
    if (dataCache.value[period]) {
      renderChart(dataCache.value[period]);
      return;
    }
    
    isLoading.value = true;
    errorMessage.value = '';
    try {
      const res = await fetch(`/investment-data?days=${period}`);
      const json = await res.json();
      
      if (!res.ok) {
        const errorMsg = json.error || `HTTP ${res.status} error`;
        const details = json.details ? JSON.stringify(json.details) : '';
        let fullMsg = details ? `${errorMsg} - ${details}` : errorMsg;
        
        if (res.status === 503) {
          fullMsg = errorMsg + ' (Rate-limited by data source. Please wait a moment and try again.)';
        }
        
        throw new Error(fullMsg);
      }

      if (json.error) {
        throw new Error(json.error);
      }

      // Validate that we have all required data
      if (!json.bitcoin || !json.ethereum || !json.sp500 || !json.gold || !json.reit) {
        throw new Error('Incomplete investment data structure received');
      }

      // Validate that arrays are not empty
      if (json.bitcoin.length === 0 || json.ethereum.length === 0 || json.sp500.length === 0 || json.gold.length === 0 || json.reit.length === 0) {
        throw new Error('One or more investment data sources returned empty results');
      }

      // Cache the data
      dataCache.value[period] = json;
      
      renderChart(json);
    } catch (error) {
      errorMessage.value = `Failed to fetch investment data: ${error.message}`;
      console.error('Investment data fetch error:', error);
    } finally {
      isLoading.value = false;
    }
  };
  
  const calculateInvestmentGrowth = (data) => {
    if (!investmentAmount.value || !startDate.value || !data) {
      return null;
    }

    // Validate data structure
    if (!data.bitcoin || !data.ethereum || !data.sp500 || !data.gold || !data.reit) {
      console.error('Invalid data structure:', data);
      return null;
    }

    const selectedStartDate = new Date(startDate.value);
    selectedStartDate.setHours(0, 0, 0, 0);

    // Ensure all arrays have data
    if (data.bitcoin.length === 0 || data.ethereum.length === 0 || data.sp500.length === 0 || data.gold.length === 0 || data.reit.length === 0) {
      console.error('One or more data arrays are empty');
      return null;
    }

    // Find the starting index for each asset
    let startIndex = 0;
    let btcStartPrice = null;
    let ethStartPrice = null;
    let sp500StartPrice = null;
    let goldStartPrice = null;
    let reitStartPrice = null;

    for (let i = 0; i < data.bitcoin.length; i++) {
      const priceDate = new Date(data.bitcoin[i][0]);
      priceDate.setHours(0, 0, 0, 0);

      if (priceDate >= selectedStartDate) {
        btcStartPrice = data.bitcoin[i][1];
        ethStartPrice = data.ethereum[i][1];
        sp500StartPrice = data.sp500[i][1];
        goldStartPrice = data.gold[i][1];
        reitStartPrice = data.reit[i][1];
        startIndex = i;
        break;
      }
    }

    if (btcStartPrice === null) {
      btcStartPrice = data.bitcoin[0][1];
      ethStartPrice = data.ethereum[0][1];
      sp500StartPrice = data.sp500[0][1];
      goldStartPrice = data.gold[0][1];
      reitStartPrice = data.reit[0][1];
      startIndex = 0;
    }

    // Calculate shares/units for each investment
    const btcAmount = investmentAmount.value / btcStartPrice;
    const ethAmount = investmentAmount.value / ethStartPrice;
    const sp500Amount = investmentAmount.value / sp500StartPrice;
    const goldAmount = investmentAmount.value / goldStartPrice;
    const reitAmount = investmentAmount.value / reitStartPrice;

    const growthData = data.bitcoin.slice(startIndex).map((p, idx) => {
      const timestamp = p[0];
      const btcPrice = p[1];
      const ethPrice = data.ethereum[startIndex + idx] ? data.ethereum[startIndex + idx][1] : data.ethereum[data.ethereum.length - 1][1];
      const sp500Price = data.sp500[startIndex + idx] ? data.sp500[startIndex + idx][1] : data.sp500[data.sp500.length - 1][1];
      const goldPrice = data.gold[startIndex + idx] ? data.gold[startIndex + idx][1] : data.gold[data.gold.length - 1][1];
      const reitPrice = data.reit[startIndex + idx] ? data.reit[startIndex + idx][1] : data.reit[data.reit.length - 1][1];
      
      // Calculate fixed returns
      const daysElapsed = idx;
      const yearsElapsed = daysElapsed / 365;
      const bondsValue = investmentAmount.value * Math.pow(1.04, yearsElapsed); // 4% annual
      const inflationValue = investmentAmount.value * Math.pow(1.02, yearsElapsed); // 2% annual

      return {
        timestamp,
        bitcoin: btcAmount * btcPrice,
        ethereum: ethAmount * ethPrice,
        sp500: sp500Amount * sp500Price,
        gold: goldAmount * goldPrice,
        bonds: bondsValue,
        reit: reitAmount * reitPrice,
        inflation: inflationValue,
      };
    });

    // Update current values
    if (growthData.length > 0) {
      const lastData = growthData[growthData.length - 1];
      values.value.bitcoin = lastData.bitcoin;
      values.value.ethereum = lastData.ethereum;
      values.value.sp500 = lastData.sp500;
      values.value.gold = lastData.gold;
      values.value.bonds = lastData.bonds;
      values.value.reit = lastData.reit;
      values.value.inflation = lastData.inflation;
    }

    return growthData;
  };
  
  const renderChart = (data) => {
    const growthData = calculateInvestmentGrowth(data);
    
    if (!growthData || growthData.length === 0) {
      return;
    }

    // Set chartData to indicate we have data
    chartData.value = growthData;
    const period = calculatedPeriod.value || 7;
    const labels = growthData.map((p, index) => {
      const date = new Date(p.timestamp);
      
      // For 1 day: show time
      if (period === 1) {
        return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
      }
      // For longer periods: show date, but skip some labels to avoid crowding
      if (period <= 30) {
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
      } else {
        const skip = period >= 365 ? 30 : 10;
        return (index % skip === 0) ? date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : '';
      }
    });

    // Build datasets based on selected vehicles
    const datasets = [];

    if (selectedVehicles.value.includes('bitcoin')) {
      datasets.push({
        label: `Bitcoin ($${investmentAmount.value.toFixed(2)})`,
        data: growthData.map(p => p.bitcoin),
        fill: false,
        borderColor: 'rgba(255, 193, 7, 1)',
        backgroundColor: 'rgba(255, 193, 7, 0.2)',
        tension: 0.3,
        borderWidth: 2,
      });
    }

    if (selectedVehicles.value.includes('ethereum')) {
      datasets.push({
        label: `Ethereum ($${investmentAmount.value.toFixed(2)})`,
        data: growthData.map(p => p.ethereum),
        fill: false,
        borderColor: 'rgba(147, 112, 219, 1)',
        backgroundColor: 'rgba(147, 112, 219, 0.2)',
        tension: 0.3,
        borderWidth: 2,
      });
    }

    if (selectedVehicles.value.includes('sp500')) {
      datasets.push({
        label: `S&P 500 ($${investmentAmount.value.toFixed(2)})`,
        data: growthData.map(p => p.sp500),
        fill: false,
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        tension: 0.3,
        borderWidth: 2,
      });
    }

    if (selectedVehicles.value.includes('gold')) {
      datasets.push({
        label: `Gold ($${investmentAmount.value.toFixed(2)})`,
        data: growthData.map(p => p.gold),
        fill: false,
        borderColor: 'rgba(255, 215, 0, 1)',
        backgroundColor: 'rgba(255, 215, 0, 0.2)',
        tension: 0.3,
        borderWidth: 2,
      });
    }

    if (selectedVehicles.value.includes('bonds')) {
      datasets.push({
        label: `Bonds (4% Annual)`,
        data: growthData.map(p => p.bonds),
        fill: false,
        borderColor: 'rgba(128, 128, 128, 1)',
        backgroundColor: 'rgba(128, 128, 128, 0.2)',
        tension: 0.3,
        borderWidth: 2,
      });
    }

    if (selectedVehicles.value.includes('reit')) {
      datasets.push({
        label: `Real Estate REIT ($${investmentAmount.value.toFixed(2)})`,
        data: growthData.map(p => p.reit),
        fill: false,
        borderColor: 'rgba(255, 165, 0, 1)',
        backgroundColor: 'rgba(255, 165, 0, 0.2)',
        tension: 0.3,
        borderWidth: 2,
      });
    }

    if (selectedVehicles.value.includes('inflation')) {
      datasets.push({
        label: `Inflation (2% Annual)`,
        data: growthData.map(p => p.inflation),
        fill: false,
        borderColor: 'rgba(255, 159, 64, 1)',
        backgroundColor: 'rgba(255, 159, 64, 0.2)',
        tension: 0.3,
        borderWidth: 2,
      });
    }
  
    if (chartInstance) {
      chartInstance.destroy();
    }
  
    const ctx = chartCanvas.value.getContext('2d');
    chartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets,
      },
      options: {
        responsive: true,
        interaction: {
          mode: 'index',
          intersect: false,
        },
        plugins: {
          tooltip: {
            mode: 'index',
            intersect: false,
            callbacks: {
              label: function(context) {
                let label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                label += '$' + context.parsed.y.toFixed(2);
                return label;
              }
            }
          }
        },
        scales: {
          y: {
            type: 'linear',
            display: true,
            title: {
              display: true,
              text: 'Portfolio Value (USD)'
            },
            beginAtZero: false
          }
        }
      }
    });
  };
  
  // Don't fetch data on mount - only fetch when user clicks Submit
  </script>
  
