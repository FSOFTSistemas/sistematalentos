// Ecclesia Landing Page Script with Mercado Pago Integration

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Mercado Pago SDK
    const mp = new MercadoPago('TEST-8b2d9c1c-9d9e-4f9a-a0e3-c4c2b3f5e6d7', {
        locale: 'pt-BR'
    });

    // Pricing data
    const plans = {
        'basico': {
            name: 'Básico',
            price: 99.00,
            description: 'Plano Básico - Até 100 membros'
        },
        'padrao': {
            name: 'Padrão',
            price: 199.00,
            description: 'Plano Padrão - Até 500 membros'
        },
        'premium': {
            name: 'Premium',
            price: 349.00,
            description: 'Plano Premium - Membros ilimitados'
        }
    };

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });

    // Handle checkout button clicks
    const checkoutButtons = document.querySelectorAll('.checkout-button');
    const checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
    
    checkoutButtons.forEach(button => {
        button.addEventListener('click', function() {
            const planId = this.getAttribute('data-plan');
            const plan = plans[planId];
            
            // Update modal with plan details
            document.getElementById('selectedPlan').textContent = plan.name;
            document.getElementById('selectedPrice').textContent = `R$ ${plan.price.toFixed(2).replace('.', ',')}`;
            
            // Store selected plan in form
            document.getElementById('checkoutForm').setAttribute('data-plan', planId);
            
            // Initialize Mercado Pago Brick
            initMercadoPagoBrick(plan);
            
            // Show checkout modal
            checkoutModal.show();
        });
    });

    // Initialize Mercado Pago Brick
    function initMercadoPagoBrick(plan) {
        const bricksBuilder = mp.bricks();
        const renderComponent = async (bricksBuilder) => {
            // Clear previous brick if exists
            const container = document.getElementById('mercadopago-bricks-container');
            container.innerHTML = '';
            
            // Create payment brick
            const settings = {
                initialization: {
                    amount: plan.price,
                    payer: {
                        email: ''
                    }
                },
                customization: {
                    paymentMethods: {
                        creditCard: 'all',
                        debitCard: 'all',
                        ticket: 'all',
                        bankTransfer: 'all',
                        mercadoPago: 'all'
                    },
                    visual: {
                        style: {
                            theme: 'default',
                            customVariables: {
                                formBackgroundColor: '#FFFFFF',
                                baseColor: '#3498db'
                            }
                        }
                    }
                },
                callbacks: {
                    onReady: () => {
                        // Brick ready to use
                        console.log('Brick ready');
                    },
                    onSubmit: (cardFormData) => {
                        // Handle form submission
                        return new Promise((resolve, reject) => {
                            // Get form data
                            const formData = {
                                name: document.getElementById('checkoutName').value,
                                email: document.getElementById('checkoutEmail').value,
                                phone: document.getElementById('checkoutPhone').value,
                                church: document.getElementById('checkoutChurch').value,
                                plan: plan.name,
                                price: plan.price,
                                payment_data: cardFormData
                            };
                            
                            // In a real implementation, you would send this data to your server
                            console.log('Payment form submitted:', formData);
                            
                            // Simulate API call to process payment
                            setTimeout(() => {
                                // Simulate successful payment
                                resolve();
                                
                                // Close modal
                                checkoutModal.hide();
                                
                                // Show success message
                                Swal.fire({
                                    title: 'Pagamento Realizado!',
                                    text: `Sua assinatura do plano ${plan.name} foi confirmada. Você receberá um email com os detalhes de acesso.`,
                                    icon: 'success',
                                    confirmButtonText: 'Continuar',
                                    confirmButtonColor: '#3498db'
                                });
                            }, 2000);
                        });
                    },
                    onError: (error) => {
                        // Handle error
                        console.error('Brick error:', error);
                        Swal.fire({
                            title: 'Erro no Pagamento',
                            text: 'Ocorreu um erro ao processar seu pagamento. Por favor, tente novamente.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3498db'
                        });
                    }
                }
            };
            
            // Render payment brick
            const paymentBrick = await bricksBuilder.create('payment', 'mercadopago-bricks-container', settings);
        };
        
        renderComponent(bricksBuilder);
    }

    // Handle confirm payment button
    document.getElementById('confirmPayment').addEventListener('click', function() {
        // Validate form
        const form = document.getElementById('checkoutForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // In a real implementation, this would trigger the Mercado Pago form submission
        // For this demo, we'll simulate a successful payment
        const planId = form.getAttribute('data-plan');
        const plan = plans[planId];
        
        // Show loading state
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processando...';
        
        // Simulate payment processing
        setTimeout(() => {
            // Reset button state
            this.disabled = false;
            this.innerHTML = 'Confirmar Pagamento';
            
            // Close modal
            checkoutModal.hide();
            
            // Show success message
            Swal.fire({
                title: 'Pagamento Realizado!',
                text: `Sua assinatura do plano ${plan.name} foi confirmada. Você receberá um email com os detalhes de acesso.`,
                icon: 'success',
                confirmButtonText: 'Continuar',
                confirmButtonColor: '#3498db'
            });
        }, 2000);
    });

    // Handle contact form submission
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            subject: document.getElementById('subject').value,
            message: document.getElementById('message').value
        };
        
        // In a real implementation, you would send this data to your server
        console.log('Contact form submitted:', formData);
        
        // Show success message
        Swal.fire({
            title: 'Mensagem Enviada!',
            text: 'Recebemos sua mensagem. Nossa equipe entrará em contato em breve.',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3498db'
        });
        
        // Reset form
        this.reset();
    });

    // Add SweetAlert2 script if not already loaded
    if (typeof Swal === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        document.body.appendChild(script);
    }
});

// Function to create Mercado Pago preference (in a real implementation, this would be done server-side)
async function createPreference(plan) {
    // This is a simulation - in a real implementation, this would be a server call
    return {
        id: 'mock_preference_id_' + Date.now(),
        init_point: '#'
    };
}

// Function to handle server-side payment processing (in a real implementation)
async function processPayment(paymentData) {
    // This is a simulation - in a real implementation, this would be a server call
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve({
                status: 'approved',
                id: 'mock_payment_id_' + Date.now()
            });
        }, 1000);
    });
}
