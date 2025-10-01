import re
from playwright.sync_api import sync_playwright, Page, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    try:
        # Navegar para a página de login na porta correta (80)
        page.goto("http://localhost/login")

        # Verificar se o título da página está correto
        expect(page).to_have_title(re.compile(r"Login"))

        # Verificar se o campo de email está visível
        email_input = page.get_by_label("Email")
        expect(email_input).to_be_visible()

        # Verificar se o botão de login, que usa Font Awesome, tem o ícone
        # Esta é uma boa verificação indireta de que o CSS foi carregado
        login_button = page.get_by_role("button", name="Log in")
        expect(login_button).to_be_visible()

        # Tirar a screenshot da página de login
        page.screenshot(path="jules-scratch/verification/login_page_verification.png", full_page=True)

        print("Screenshot da página de login capturada com sucesso.")

    except Exception as e:
        print(f"Ocorreu um erro durante a verificação da página de login: {e}")
        page.screenshot(path="jules-scratch/verification/login_error_screenshot.png")
    finally:
        context.close()
        browser.close()

with sync_playwright() as playwright:
    run(playwright)