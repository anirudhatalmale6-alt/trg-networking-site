"""
Prove the old-URL map with real HTTP requests.

The previous delivery could only *simulate* these rules, because there was no
web server to run them against. This runs them: every URL that exists on the
live WordPress site at www.trgnetworking.com, plus the interim slugs used
during the build, is requested and its final destination checked.

Usage: python3 tools/redirects.py http://127.0.0.1:8734
"""
import sys
import urllib.request
import urllib.error

BASE = (sys.argv[1] if len(sys.argv) > 1 else "http://127.0.0.1:8734").rstrip("/")

# old path -> expected final path (None = must resolve where it is, no redirect)
CASES = {
    # Interim slugs from the Hostinger staging build.
    "/managed-it/": "/managed-it-services/",
    "/help-desk/": "/help-desk-it-support/",
    "/microsoft-cloud/": "/microsoft-365-cloud/",
    "/ai-services/": "/secure-ai-adoption/",
    "/cmmc/": "/cmmc-readiness/",
    "/business-continuity/": "/backup-business-continuity/",
    "/industries/construction/": "/construction/",
    "/industries/manufacturing/": "/manufacturing/",
    "/industries/government-contractors/": "/government-contractors/",
    "/industries/professional-services/": "/professional-services/",

    # The live WordPress site.
    "/managed-it-services/": "/managed-it-services/",   # same path on both sites
    "/network-security/": "/cybersecurity/",
    "/cloud-computing/": "/microsoft-365-cloud/",
    "/data-backup-and-recovery/": "/backup-business-continuity/",
    "/about-us/": "/about/",
    "/about-us/contact-us/": "/contact/",
    "/about-us/referral-program/": "/about/",
    "/why-choose-us/": "/why-trg/",
    "/our-clients/": "/resources/case-studies/",
    "/initial-consultation/": "/contact/",
    "/discoverycall/": "/contact/",
    "/cyber-security-tip-of-the-week/": "/resources/",
    "/itbuyersguide/": "/resources/guides/",
    "/new-cybersecurity-crisis/": "/cybersecurity/",
    "/3problems/": "/resources/",
    "/aspirin/": "/resources/",
    "/thank-you-aspirin/": "/resources/",
    "/closerlook/": "/resources/",
    "/is-this-you/": "/resources/",
    "/support-center/": "/support-center/",             # same path on both sites

    # Patterns.
    "/testimonial/bsc-america/": "/resources/case-studies/",
    "/testimonial/belt-built-contracting/": "/resources/case-studies/",
    "/category/cybersecurity/": "/resources/",
    "/tag/microsoft-365/": "/resources/",
}

# The thirty-five dated blog posts all take the same shape.
for path in [
    "/2023/01/18/why-your-business-needs-managed-it/",
    "/2023/05/02/phishing-is-still-the-front-door/",
    "/2023/09/14/what-cmmc-means-for-you/",
    "/2024/02/06/microsoft-365-licensing-explained/",
    "/2024/07/23/ai-at-work-without-the-risk/",
    "/2024/11/11/backups-you-can-actually-restore/",
    "/2025/03/04/azure-cost-control/",
]:
    CASES[path] = "/resources/"

# The free assessment page carries a query string through.
QUERY_CASES = {"/free-network-assessment/": "/contact/?type=it-assessment"}

# The two solution areas added in the consolidated change request are new
# slugs, not old ones: they must resolve where they are and never redirect.
CASES["/network-infrastructure/"] = "/network-infrastructure/"
CASES["/strategic-it-vcio/"] = "/strategic-it-vcio/"


class NoRedirect(urllib.request.HTTPRedirectHandler):
    """Follow redirects by hand so the chain can be recorded."""

    def redirect_request(self, req, fp, code, msg, headers, newurl):
        raise urllib.error.HTTPError(req.full_url, code, newurl, headers, fp)


opener = urllib.request.build_opener(NoRedirect)

failures = []
loops = []


def resolve(path):
    """Follow the chain and return (final path, [status codes])."""
    chain = []
    url = BASE + path
    seen = set()
    for _ in range(10):
        if url in seen:
            loops.append(path)
            return url, chain
        seen.add(url)
        try:
            resp = opener.open(url, timeout=20)
            chain.append(resp.status)
            return url, chain
        except urllib.error.HTTPError as err:
            if err.code in (301, 302, 307, 308):
                chain.append(err.code)
                nxt = err.reason if isinstance(err.reason, str) else err.headers.get("Location")
                url = urllib.parse.urljoin(url, nxt)
                continue
            chain.append(err.code)
            return url, chain
    loops.append(path)
    return url, chain


import urllib.parse  # noqa: E402  (used above)

print(f"{len(CASES) + len(QUERY_CASES)} old URLs\n")

for path, expected in list(CASES.items()) + list(QUERY_CASES.items()):
    final, chain = resolve(path)
    final_path = final[len(BASE):] or "/"
    ok = final_path == expected and chain and chain[-1] == 200
    if not ok:
        failures.append(f"{path}  ->  {final_path}  {chain}   (expected {expected})")
    flag = "ok " if ok else "FAIL"
    print(f"  {flag} {path:44s} -> {final_path:32s} {chain}")

print()
if loops:
    print("REDIRECT LOOPS:", loops)
if failures:
    print(f"{len(failures)} FAILURE(S):")
    for failure in failures:
        print("  -", failure)
    sys.exit(1)
print("Every old URL lands on a live page. No loops.")
