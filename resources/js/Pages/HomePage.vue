<template>
    <div class="flex min-h-screen bg-gray-100">
      <!-- Sidebar -->
      <aside class="w-64 bg-white shadow-md p-6 fixed h-full">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
          <i class="fa fa-chart-pie text-blue-600 mr-2"></i> Financial App
        </h2>
        <nav>
          <ul class="space-y-4">
            <li>
              <a href="#" class="flex items-center text-gray-700 hover:text-blue-600">
                <i class="fa fa-tachometer-alt mr-3"></i> Dashboard
              </a>
            </li>
            <li>
              <a href="#" class="flex items-center text-gray-700 hover:text-blue-600">
                <i class="fa fa-wallet mr-3"></i> Accounts
              </a>
            </li>
            <li>
              <a href="#" class="flex items-center text-gray-700 hover:text-blue-600">
                <i class="fa fa-exchange-alt mr-3"></i> Transactions
              </a>
            </li>
            <li>
              <a href="#" class="flex items-center text-gray-700 hover:text-blue-600">
                <i class="fa fa-file-invoice-dollar mr-3"></i> Reports
              </a>
            </li>
            <li>
              <a href="#" class="flex items-center text-gray-700 hover:text-blue-600">
                <i class="fa fa-cogs mr-3"></i> Settings
              </a>
            </li>
          </ul>
        </nav>
      </aside>

      <!-- Main Content -->
      <div class="flex-1 ml-64 flex flex-col h-screen">
        <!-- Top Navbar -->
        <header class="bg-white shadow-md p-4 flex justify-between items-center fixed w-full left-64 right-0">
          <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
          <div class="flex items-center space-x-4">
            <i class="fa fa-bell text-gray-600 text-lg"></i>
            <div class="relative">
              <img src="../../assets/img/rep.png" class="w-10 h-10 rounded-full" alt="Profile">
              <div class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md hidden">
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
              </div>
            </div>
          </div>
        </header>

        <!-- Dashboard Content -->
        <div class="p-6 mt-16 overflow-y-auto flex-1">
          <!-- Summary Cards -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 shadow-lg rounded-lg flex items-center">
              <i class="fa fa-chart-line text-green-500 text-3xl mr-4"></i>
              <div>
                <h2 class="text-gray-500 text-sm uppercase">Total Revenue</h2>
                <p class="text-2xl font-semibold text-gray-800">$50,000</p>
              </div>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg flex items-center">
              <i class="fa fa-money-bill text-red-500 text-3xl mr-4"></i>
              <div>
                <h2 class="text-gray-500 text-sm uppercase">Total Expenses</h2>
                <p class="text-2xl font-semibold text-gray-800">$30,000</p>
              </div>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg flex items-center">
              <i class="fa fa-wallet text-blue-500 text-3xl mr-4"></i>
              <div>
                <h2 class="text-gray-500 text-sm uppercase">Net Balance</h2>
                <p class="text-2xl font-semibold text-gray-800">$20,000</p>
              </div>
            </div>
          </div>

          <!-- Recent Transactions -->
          <div class="mt-8 bg-white p-6 shadow-lg rounded-lg">
            <h2 class="text-xl font-bold mb-4 text-gray-800 flex items-center">
              <i class="fa fa-exchange-alt mr-2"></i> Recent Transactions
            </h2>
            <table class="w-full border-collapse text-gray-700">
              <thead>
                <tr class="bg-gray-200 text-gray-600 text-left text-sm uppercase">
                  <th class="p-3">Date</th>
                  <th class="p-3">Account</th>
                  <th class="p-3">Type</th>
                  <th class="p-3">Amount</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="transaction in transactions" :key="transaction.id" class="border-t hover:bg-gray-50">
                  <td class="p-3">{{ transaction.date }}</td>
                  <td class="p-3">{{ transaction.account }}</td>
                  <td class="p-3">
                    <span class="px-2 py-1 text-xs font-semibold rounded-md" :class="{
                      'bg-green-200 text-green-700': transaction.type === 'Credit',
                      'bg-red-200 text-red-700': transaction.type === 'Debit'
                    }">{{ transaction.type }}</span>
                  </td>
                  <td class="p-3 font-semibold">${{ transaction.amount }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        transactions: [
          { id: 1, date: '2024-09-15', account: 'Main Account', type: 'Credit', amount: '5,000' },
          { id: 2, date: '2024-09-14', account: 'Expense Account', type: 'Debit', amount: '2,500' },
          { id: 3, date: '2024-09-13', account: 'Revenue Account', type: 'Credit', amount: '3,000' },
          { id: 1, date: '2024-09-15', account: 'Main Account', type: 'Credit', amount: '5,000' },
          { id: 2, date: '2024-09-14', account: 'Expense Account', type: 'Debit', amount: '2,500' },
          { id: 3, date: '2024-09-13', account: 'Revenue Account', type: 'Credit', amount: '3,000' },
          { id: 1, date: '2024-09-15', account: 'Main Account', type: 'Credit', amount: '5,000' },
          { id: 2, date: '2024-09-14', account: 'Expense Account', type: 'Debit', amount: '2,500' },
          { id: 3, date: '2024-09-13', account: 'Revenue Account', type: 'Credit', amount: '3,000' },
          { id: 1, date: '2024-09-15', account: 'Main Account', type: 'Credit', amount: '5,000' },
          { id: 2, date: '2024-09-14', account: 'Expense Account', type: 'Debit', amount: '2,500' },
          { id: 3, date: '2024-09-13', account: 'Revenue Account', type: 'Credit', amount: '3,000' },
          { id: 1, date: '2024-09-15', account: 'Main Account', type: 'Credit', amount: '5,000' },
          { id: 2, date: '2024-09-14', account: 'Expense Account', type: 'Debit', amount: '2,500' },
          { id: 3, date: '2024-09-13', account: 'Revenue Account', type: 'Credit', amount: '3,000' },
          { id: 1, date: '2024-09-15', account: 'Main Account', type: 'Credit', amount: '5,000' },
          { id: 2, date: '2024-09-14', account: 'Expense Account', type: 'Debit', amount: '2,500' },
          { id: 3, date: '2024-09-13', account: 'Revenue Account', type: 'Credit', amount: '3,000' },
        ],
      };
    },
  };
  </script>

  <style>
  /* Ensures the sidebar and top navbar remain fixed */
  body {
    overflow: hidden;
  }
  </style>
