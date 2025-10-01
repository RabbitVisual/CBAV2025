import re
from playwright.sync_api import sync_playwright, Page, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    try:
        # Navegar para a página de login
        page.goto("http://localhost:8000/login")

        # Preencher credenciais e fazer login
        page.get_by_label("Email").fill("member@example.com")
        page.get_by_label("Password").fill("password")
        page.get_by_role("button", name="Log in").click()

        # Esperar pelo redirecionamento para o dashboard
        expect(page).to_have_url(re.compile(r".*/member"))

        # Verificar se o cabeçalho de boas-vindas está visível
        welcome_header = page.get_by_role("heading", name=re.compile(r"Bem-vindo,.*"))
        expect(welcome_header).to_be_visible()

        # Tirar a screenshot da página inteira
        page.screenshot(path="jules-scratch/verification/dashboard_verification.png", full_page=True)

        print("Screenshot do dashboard capturada com sucesso.")

    except Exception as e:
        print(f"Ocorreu um erro durante a verificação: {e}")
        # Em caso de erro, tirar uma screenshot para depuração
        page.screenshot(path="jules-scratch/verification/error_screenshot.png")
    finally:
        context.close()
        browser.close()

with sync_playwright() as playwright:
    run(playwright)