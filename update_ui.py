import os
import re

directories = [
    'resources/views/livewire/',
    'resources/views/layouts/',
    'resources/views/components/'
]

skip_files = [
    'header.blade.php',
    'dashboard.blade.php'
]

# Patterns to replace text sizes
size_patterns = [
    r'text-xs', r'text-sm', r'text-\[8px\]', r'text-\[9px\]', 
    r'text-\[10px\]', r'text-\[11px\]', r'text-\[12px\]', 
    r'text-\[13px\]', r'text-\[14px\]', r'text-\[15px\]'
]

color_replaces = {
    'text-slate-400': 'text-slate-800',
    'text-slate-500': 'text-slate-900',
    'text-slate-600': 'text-slate-900',
    'text-slate-700': 'text-black'
}

count = 0

for d in directories:
    for root, dirs, files in os.walk(d):
        for file in files:
            if file.endswith('.blade.php') and file not in skip_files:
                filepath = os.path.join(root, file)
                try:
                    with open(filepath, 'r', encoding='utf-8') as f:
                        content = f.read()
                    
                    original_content = content
                    
                    # Update sizes
                    for pattern in size_patterns:
                        content = re.sub(r'\b' + pattern + r'\b', 'text-[15px]', content)
                    
                    # Update colors
                    for old_c, new_c in color_replaces.items():
                        content = content.replace(old_c, new_c)
                        
                    if content != original_content:
                        with open(filepath, 'w', encoding='utf-8') as f:
                            f.write(content)
                        count += 1
                        print(f"Updated {filepath}")
                except Exception as e:
                    print(f"Error on {filepath}: {e}")

print(f"Total files updated: {count}")
