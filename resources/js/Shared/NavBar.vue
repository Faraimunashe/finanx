<template>
    <header class="bg-white shadow-md fixed top-0 inset-x-0 z-50">
      <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo -->
        <div class="text-2xl font-bold text-emerald-600">
          <i class="fas fa-leaf mr-2"></i>Finanx
        </div>

        <!-- Navigation Links -->
        <div class="flex items-center space-x-6">
          <!-- Dashboard -->
          <Link :href="dashboardRoute" class="text-sm hover:text-emerald-600 flex items-center">
            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
          </Link>

          <!-- Accounts -->
          <Link :href="accountsRoute" class="text-sm hover:text-emerald-600 flex items-center">
            <i class="fas fa-wallet mr-2"></i>Accounts
          </Link>

          <!-- Transactions -->
          <Link :href="transactionsRoute" class="text-sm hover:text-emerald-600 flex items-center">
            <i class="fas fa-exchange-alt mr-2"></i>Transactions
          </Link>

          <!-- Treasurer Only Features -->
          <template v-if="isTreasurer">
            <!-- Organizations -->
            <Link href="/organizations" class="text-sm hover:text-emerald-600 flex items-center">
              <i class="fas fa-building mr-2"></i>Organizations
            </Link>

            <!-- Reports Dropdown -->
            <div class="relative" ref="reportsDropdown">
              <button @click="toggleReportsDropdown" class="text-sm hover:text-emerald-600 flex items-center">
                <i class="fas fa-chart-bar mr-2"></i>Reports
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

            <!-- Currencies -->
            <Link href="/currencies" class="text-sm hover:text-emerald-600 flex items-center">
              <i class="fas fa-coins mr-2"></i>Currencies
            </Link>

            <!-- User Management -->
            <Link href="/users" class="text-sm hover:text-emerald-600 flex items-center">
              <i class="fas fa-users mr-2"></i>Users
            </Link>
          </template>

                              <!-- User Only Features -->
                    <template v-if="isUser">
                      <!-- Organization Selector -->
                      <div class="relative" ref="orgDropdown">
                        <button @click="toggleOrgDropdown" class="text-sm hover:text-emerald-600 flex items-center">
                          <i class="fas fa-building mr-2"></i>Select Org
                          <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div
                          v-if="showOrgDropdown"
                          class="absolute top-full left-0 mt-2 w-64 bg-white border border-gray-200 shadow-lg rounded-lg z-50"
                        >
                          <div class="p-3 border-b bg-gray-50">
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Organizations</p>
                          </div>
                          <ul class="py-2">
                            <li>
                              <button
                                @click="selectOrganization"
                                class="w-full flex items-center px-4 py-2 text-sm hover:bg-gray-100 text-left"
                              >
                                <i class="fas fa-building mr-3 text-emerald-500"></i> Organization 1
                              </button>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </template>

          <!-- Avatar + Dropdown -->
          <div class="relative">
            <div
              class="w-10 h-10 rounded-full border-2 border-emerald-500 cursor-pointer bg-emerald-100 flex items-center justify-center"
              @click="toggleDropdown"
            >
              <i class="fas fa-user text-emerald-600"></i>
            </div>
            <div
              v-if="showDropdown"
              class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 shadow-lg rounded-lg z-50"
            >
              <div class="p-4 border-b text-center">
                <p class="font-semibold text-gray-800">{{ username }}</p>
                <p class="text-sm text-gray-500">{{ userRole }}</p>
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
                <li class="border-t">
                  <form @submit.prevent="logout" class="w-full">
                    <button type="submit" class="w-full flex items-center px-4 py-2 hover:bg-gray-100 text-red-600">
                      <i class="fas fa-sign-out-alt mr-3 text-red-500"></i> Logout
                    </button>
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </header>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
  components: {
    Link,
  },
  props: {
    username: {
      type: String,
      default: 'User'
    },
    role: {
      type: String,
      default: 'User'
    }
  },
  data() {
    return {
      showDropdown: false,
      showReportsDropdown: false,
      showOrgDropdown: false,
    };
  },
  computed: {
    isTreasurer() {
      return this.role === 'treasurer';
    },
    isUser() {
      return this.role === 'user';
    },
    userRole() {
      return this.role;
    },
    dashboardRoute() {
      if (this.isTreasurer) {
        return '/dashboard';
      } else if (this.isUser) {
        return '/user/dashboard';
      }
      return '/dashboard';
    },
    accountsRoute() {
      if (this.isTreasurer) {
        return '/accounts';
      } else if (this.isUser) {
        return '/user/accounts';
      }
      return '/accounts';
    },
    transactionsRoute() {
      if (this.isTreasurer) {
        return '/transactions';
      } else if (this.isUser) {
        return '/user/transactions';
      }
      return '/transactions';
    },
  },
  methods: {
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
      this.showReportsDropdown = false;
      this.showOrgDropdown = false;
    },
    toggleReportsDropdown() {
      this.showReportsDropdown = !this.showReportsDropdown;
      this.showDropdown = false;
      this.showOrgDropdown = false;
    },
    toggleOrgDropdown() {
      this.showOrgDropdown = !this.showOrgDropdown;
      this.showDropdown = false;
      this.showReportsDropdown = false;
    },
                selectOrganization() {
          // This would typically make an API call to select the organization
          this.showOrgDropdown = false;

          // You can implement the actual organization selection logic here
          // For now, we'll just update the local state
        },
    logout() {
      // Implement logout functionality
      this.$inertia.post('/logout');
    },
    handleClickOutside(e) {
      const avatar = this.$el.querySelector(".w-10.h-10");
      const dropdown = this.$el.querySelector(".absolute");
      const reportsDropdown = this.$refs.reportsDropdown;
      const orgDropdown = this.$refs.orgDropdown;

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

      if (
        orgDropdown &&
        !orgDropdown.contains(e.target)
      ) {
        this.showOrgDropdown = false;
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

