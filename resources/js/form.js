// Form handling
document.addEventListener('alpine:init', () => {
    Alpine.data('enquiryForm', () => ({
        loading: false,
        success: false,
        message: '',
        formData: {
            company_name: '',
            contact_person: '',
            email: '',
            phone: '',
            product_category: '',
            quantity: '',
            message: ''
        },
        async submitForm() {
            this.loading = true;
            this.message = '';
            try {
                const response = await fetch('/enquiry', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || CSRF
                    },
                    body: JSON.stringify(this.formData)
                });
                const data = await response.json();
                if (response.ok) {
                    this.success = true;
                    this.message = 'Enquiry sent successfully!';
                    this.formData = {
                        company_name: '',
                        contact_person: '',
                        email: '',
                        phone: '',
                        product_category: '',
                        quantity: '',
                        message: ''
                    };
                } else {
                    this.success = false;
                    this.message = data.message || 'Something went wrong. Please try again.';
                }
            } catch (error) {
                this.success = false;
                this.message = 'Failed to connect to the server.';
            } finally {
                this.loading = false;
            }
        }
    }));
});
