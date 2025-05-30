<template>
    <header class="bg-white shadow-md fixed top-0 inset-x-0 z-50">
      <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="text-2xl font-bold text-emerald-600">
          <i class="fas fa-leaf mr-2"></i>FinAccounts
        </div>

        <!-- Navigation Links -->
        <div class="flex items-center space-x-6">
          <Link href="/dashboard" class="text-sm hover:text-emerald-600">Dashboard</Link>
          <Link href="/accounts" class="text-sm hover:text-emerald-600">Accounts</Link>
          <Link href="/transactions" class="text-sm hover:text-emerald-600">Transactions</Link>
          <button class="text-sm hover:text-emerald-600">Reports</button>
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
      user: {
        name: "John Doe",
        role: "Finance Manager",
      },
    };
  },
  methods: {
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
    },
  },
  mounted() {
    document.addEventListener("click", this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener("click", this.handleClickOutside);
  },
  methods: {
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
    },
    handleClickOutside(e) {
      const avatar = this.$el.querySelector("img");
      const dropdown = this.$el.querySelector(".absolute");
      if (
        avatar &&
        dropdown &&
        !avatar.contains(e.target) &&
        !dropdown.contains(e.target)
      ) {
        this.showDropdown = false;
      }
    },
  },
};
</script>

