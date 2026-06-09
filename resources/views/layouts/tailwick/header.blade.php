<!-- Topbar Start -->
<div class="app-header min-h-topbar-height flex items-center sticky top-0 z-30 bg-(--topbar-background) border-b border-default-200">
    <div class="w-full flex items-center justify-between px-6">
        <div class="flex items-center gap-5">
            <!-- Sidenav Menu Toggle Button -->
            <button id="button-toggle-menu" class="btn btn-icon size-9 bg-default-400/10 hover:bg-default-150 rounded">
                <i class="iconify lucide--align-left text-xl"></i>
            </button>
        </div>

        <div class="flex items-center gap-3">
            <!-- Light/Dark Mode Button -->
            <div class="topbar-item">
                <button class="btn btn-icon size-8 hover:bg-default-150 transition-[scale] rounded-full" id="light-dark-mode" type="button">
                    <i class="iconify tabler--moon text-xl absolute dark:scale-0 dark:-rotate-90 scale-100 rotate-0 transition-all duration-200"></i>
                    <i class="iconify tabler--sun text-xl absolute dark:scale-100 dark:rotate-0 scale-0 rotate-90 transition-all duration-200"></i>
                </button>
            </div>

            <!-- Profile Dropdown Button -->
            <div class="topbar-item hs-dropdown relative inline-flex">
                <button class="cursor-pointer bg-pink-100 rounded-full" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                    <div class="size-9.5 rounded-full flex justify-center items-center font-bold text-slate-700 bg-slate-200">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                </button>

                <div class="hs-dropdown-menu min-w-48" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-with-icons">
                    <div class="p-2">
                        <h6 class="mb-2 text-default-500">Welcome</h6>

                        <a href="#!" class="flex gap-3">
                            <div class="relative inline-block">
                                <div class="rounded bg-default-200 size-12 flex justify-center items-center font-bold text-slate-700 text-xl">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                                </div>
                                <span class="-top-1 -end-1 absolute size-2.5 bg-green-400 border-2 border-white rounded-full"></span>
                            </div>

                            <div>
                                <h6 class="mb-1 text-sm font-semibold text-default-800">{{ auth()->user()->name ?? 'Admin' }}</h6>
                                <p class="text-default-500">{{ auth()->user()->roles->first()->name ?? 'Super Admin' }}</p>
                            </div>
                        </a>
                    </div>

                    <div class="border-t border-t-default-200 -mx-2 my-2"></div>

                    <div class="flex flex-col gap-y-1">
                        <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded" href="{{ route('admin.profile') }}">
                            <i data-lucide="user" class="size-4"></i>
                            My Profile
                        </a>

                        <div class="border-t border-default-200 -mx-2 my-1"></div>

                        <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded" href="{{ route('logout') }}">
                            <i data-lucide="log-out" class="size-4"></i>
                            Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Topbar End -->
