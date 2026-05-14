import re

with open('procbt-installation.html', 'r') as f:
    content = f.read()

# This pattern matches the content INSIDE code-block divs more aggressively
# We need to handle the case where first line is on same line as opening tag
# Pattern: opening_tag + first_line\n + indented_lines... + </div>
pattern = re.compile(
    r'(<div class="code-block"[^>]*>)([^\n]*\n)((?:[ \t]+[^\n]*\n?)*)(</div>)'
)

def fix_block(match):
    tag = match.group(1)
    first_line = match.group(2)   # first line (on same line as tag), ends with \n
    rest = match.group(3)          # subsequent indented lines
    closing = match.group(4)
    
    if not rest:
        return match.group(0)
    
    rest_lines = rest.split('\n')
    non_empty = [l for l in rest_lines if l.strip()]
    if not non_empty:
        return match.group(0)
    
    min_indent = min(len(l) - len(l.lstrip()) for l in non_empty)
    
    fixed_rest = []
    for l in rest_lines:
        if l.strip():
            fixed_rest.append(l[min_indent:] if len(l) >= min_indent else l.lstrip())
        else:
            fixed_rest.append('')
    
    return tag + first_line + '\n'.join(fixed_rest) + closing

new_content = pattern.sub(fix_block, content)

with open('procbt-installation.html', 'w') as f:
    f.write(new_content)

# Verify
import subprocess
result = subprocess.run(['grep', '-A', '3', 'id="c4b"', 'procbt-installation.html'], 
                      capture_output=True, text=True)
print(result.stdout[:300])

result2 = subprocess.run(['grep', '-A', '3', 'id="c19b"', 'procbt-installation.html'], 
                       capture_output=True, text=True)
print(result2.stdout[:300])
