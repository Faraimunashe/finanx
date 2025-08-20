<template>
    <header class="bg-white shadow-md fixed top-0 inset-x-0 z-50">
      <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="text-2xl font-bold text-emerald-600">
          <i class="fas fa-leaf mr-2"></i>Finanx
        </div>

        <!-- Navigation Links -->
        <div class="flex items-center space-x-6">
          <Link href="/dashboard" class="text-sm hover:text-emerald-600">Home</Link>
          <Link href="/accounts" class="text-sm hover:text-emerald-600">Accounts</Link>
          <Link href="/transactions" class="text-sm hover:text-emerald-600">Transactions</Link>

          <!-- Reports Dropdown -->
          <div class="relative" ref="reportsDropdown">
            <button @click="toggleReportsDropdown" class="text-sm hover:text-emerald-600 flex items-center">
              Reports
              <i class="fas fa-chevron-down ml-1 text-xs"></i>
            </button>
            <div
              v-if="showReportsDropdown"
              class="absolute top-full left-0 mt-2 w-64 bg-white border border-gray-200 shadow-lg rounded-lg z-50"
            >
              <div class="p-3 border-b bg-gray-50">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Financial Reports</p>
              </div>
              <ul class="py-2">
                <li>
                  <Link href="/reports" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
                    <i class="fas fa-chart-bar mr-3 text-emerald-500"></i> Reports Dashboard
                  </Link>
                </li>
                <li>
                  <Link href="/reports/balance-sheet" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
                    <i class="fas fa-balance-scale mr-3 text-blue-500"></i> Balance Sheet
                  </Link>
                </li>
                <li>
                  <Link href="/reports/income-statement" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
                    <i class="fas fa-chart-line mr-3 text-green-500"></i> Income Statement
                  </Link>
                </li>
                <li>
                  <Link href="/reports/cash-flow" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
                    <i class="fas fa-money-bill-wave mr-3 text-emerald-500"></i> Cash Flow
                  </Link>
                </li>
                <li>
                  <Link href="/reports/trial-balance" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
                    <i class="fas fa-calculator mr-3 text-purple-500"></i> Trial Balance
                  </Link>
                </li>
                <li>
                  <Link href="/reports/general-ledger" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
                    <i class="fas fa-book mr-3 text-orange-500"></i> General Ledger
                  </Link>
                </li>
              </ul>
            </div>
          </div>

          <Link href="/currencies" class="text-sm hover:text-emerald-600">Currencies</Link>

          <!-- Avatar + Dropdown -->
          <div class="relative">
            <img
              src="../../assets/img/rep.png"
              class="w-10 h-10 rounded-full border-2 border-emerald-500 cursor-pointer"
              alt="User"
              @click="toggleDropdown"
            />
            <div
              v-if="showDropdown"
              class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 shadow-lg rounded-lg z-50"
            >
              <div class="p-4 border-b text-center">
                <p class="font-semibold text-gray-800">{{ user.name }}</p>
                <p class="text-sm text-gray-500">{{ user.role }}</p>
              </div>
              <ul class="py-2 text-sm text-gray-700">
                <li>
                  <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100">
                    <i class="fas fa-user mr-3 text-emerald-500"></i> Profile
                  </a>
                </li>
                <li>
                  <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100">
                    <i class="fas fa-cog mr-3 text-emerald-500"></i> Settings
                  </a>
                </li>
                <li>
                  <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100 text-red-600">
                    <i class="fas fa-sign-out-alt mr-3 text-red-500"></i> Logout
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </header>
</template>

<script>
export default {
  data() {
    return {
      showDropdown: false,
      showReportsDropdown: false,
      user: {
        name: "John Doe",
        role: "Finance Manager",
      },
    };
  },
  methods: {
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
      this.showReportsDropdown = false;
    },
    toggleReportsDropdown() {
      this.showReportsDropdown = !this.showReportsDropdown;
      this.showDropdown = false;
    },
    handleClickOutside(e) {
      const avatar = this.$el.querySelector("img");
      const dropdown = this.$el.querySelector(".absolute");
      const reportsDropdown = this.$refs.reportsDropdown;

      if (
        avatar &&
        dropdown &&
        !avatar.contains(e.target) &&
        !dropdown.contains(e.target)
      ) {
        this.showDropdown = false;
      }

      if (
        reportsDropdown &&
        !reportsDropdown.contains(e.target)
      ) {
        this.showReportsDropdown = false;
      }
    },
  },
  mounted() {
    document.addEventListener("click", this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener("click", this.handleClickOutside);
  },
};
</script>

