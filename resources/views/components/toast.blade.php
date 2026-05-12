<div
	x-data="{
		toasts: [],
		addToast(detail) {
			const id = crypto.randomUUID();
			this.toasts.push({
				id,
				message: detail.message || 'Saved',
				type: detail.type || 'success',
			});
			setTimeout(() => this.removeToast(id), 3600);
		},
		removeToast(id) {
			this.toasts = this.toasts.filter((toast) => toast.id !== id);
		},
	}"
	x-on:toast.window="addToast($event.detail)"
	class="pointer-events-none fixed right-4 top-6 z-[60] flex w-full max-w-sm flex-col gap-3"
	aria-live="polite"
>
	<template x-for="toast in toasts" :key="toast.id">
		<div
			class="pointer-events-auto relative overflow-hidden rounded-2xl border bg-white/95 px-4 py-3 text-sm shadow-xl backdrop-blur transition dark:bg-slate-950/90"
			:class="toast.type === 'error'
				? 'border-rose-200 text-rose-700 shadow-rose-500/10 dark:border-rose-900/60 dark:text-rose-200'
				: toast.type === 'warning'
					? 'border-amber-200 text-amber-800 shadow-amber-500/10 dark:border-amber-900/60 dark:text-amber-200'
					: toast.type === 'info'
						? 'border-sky-200 text-sky-700 shadow-sky-500/10 dark:border-sky-900/60 dark:text-sky-200'
						: 'border-emerald-200 text-emerald-700 shadow-emerald-500/10 dark:border-emerald-900/60 dark:text-emerald-200'"
			x-transition.opacity.scale.95
		>
			<span
				class="absolute inset-y-0 left-0 w-1"
				:class="toast.type === 'error'
					? 'bg-rose-500'
					: toast.type === 'warning'
						? 'bg-amber-500'
						: toast.type === 'info'
							? 'bg-sky-500'
							: 'bg-emerald-500'"
			></span>
			<div class="flex items-start gap-3">
				<div
					class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-full"
					:class="toast.type === 'error'
						? 'bg-rose-100 text-rose-600 dark:bg-rose-900/40 dark:text-rose-200'
						: toast.type === 'warning'
							? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-200'
							: toast.type === 'info'
								? 'bg-sky-100 text-sky-600 dark:bg-sky-900/40 dark:text-sky-200'
								: 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-200'"
				>
					<svg x-show="toast.type === 'success'" viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
					</svg>
					<svg x-show="toast.type === 'error'" x-cloak viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
					</svg>
					<svg x-show="toast.type === 'warning'" x-cloak viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
					</svg>
					<svg x-show="toast.type === 'info'" x-cloak viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M12 9h.01M11 12h1v4m0 4a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
					</svg>
				</div>
				<div class="flex-1">
					<p
						class="font-medium text-slate-900 dark:text-white"
						x-text="toast.type === 'error'
							? 'Action failed'
							: toast.type === 'warning'
								? 'Warning'
								: toast.type === 'info'
									? 'FYI'
									: 'Success'"
					></p>
					<p class="mt-0.5 text-slate-600 dark:text-slate-300" x-text="toast.message"></p>
				</div>
				<button
					type="button"
					class="rounded-lg p-1 text-slate-400 transition hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300"
					@click="removeToast(toast.id)"
					aria-label="Dismiss notification"
				>
					<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8">
						<path stroke-linecap="round" d="M6 6l12 12M18 6l-12 12" />
					</svg>
				</button>
			</div>
		</div>
	</template>
</div>
