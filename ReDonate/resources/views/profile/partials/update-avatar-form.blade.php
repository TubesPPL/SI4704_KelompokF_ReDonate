<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Foto Profil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Perbarui foto profil Anda agar donatur dan penerima lebih mudah mengenali Anda.
        </p>
    </header>

    <form method="post" action="{{ route('profile.avatar.upload') }}" enctype="multipart/form-data" class="mt-6 space-y-6" x-data="avatarPreview()">
        @csrf

        <div class="flex items-center space-x-6">
            <div class="shrink-0">
                <template x-if="imageUrl">
                    <img class="h-24 w-24 object-cover rounded-full shadow-md" :src="imageUrl" alt="Preview Avatar" />
                </template>
                <template x-if="!imageUrl">
                    <img class="h-24 w-24 object-cover rounded-full shadow-md" src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ $user->name }}" />
                </template>
            </div>
            <label class="block">
                <span class="sr-only">Pilih foto profil</span>
                <input type="file" name="avatar" accept="image/*" @change="fileChosen" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-teal-50 file:text-teal-700
                    hover:file:bg-teal-100
                "/>
            </label>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />

        <div class="flex items-center gap-4">
            <x-primary-button x-bind:disabled="!hasFile || submitting" x-text="submitting ? 'Mengunggah...' : 'Unggah'" @click="submitting = true"></x-primary-button>

            @if (session('status') === 'avatar-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>

    <script>
        function avatarPreview() {
            return {
                imageUrl: null,
                hasFile: false,
                submitting: false,
                fileChosen(event) {
                    this.fileToDataUrl(event, src => this.imageUrl = src);
                    this.hasFile = event.target.files.length > 0;
                },
                fileToDataUrl(event, callback) {
                    if (! event.target.files.length) return;
                    let file = event.target.files[0];
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = e => callback(e.target.result);
                },
            }
        }
    </script>
</section>
