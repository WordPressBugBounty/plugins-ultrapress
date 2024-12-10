<?php
/**
 * Tips carousel component
 *
 * @since      1.0.0
 *
 * @package    Ultrapress
 * @subpackage Ultrapress/admin/partials
 */
?>
<!-- Tips Carousel -->
<div class="tips-carousel-container" id="tips-carousel-container">
    <div class="tips-carousel">
        <!-- Basic Operations -->
        <div class="tip-slide active">
            <span class="tip-icon">💡</span>
            <span class="tip-text">Welcome to UltraPress! Double-click anywhere in the workspace to add your first component. Components are the building blocks of your automation.</span>
        </div>
        <div class="tip-slide">
            <span class="tip-icon">🔗</span>
            <span class="tip-text">Connect components by dragging from red dots (outputs) to green dots (inputs). This creates a flow between your components, determining how they interact.</span>
        </div>
        <div class="tip-slide">
            <span class="tip-icon">⚙️</span>
            <span class="tip-text">Click any component to open its settings panel. Here you can configure parameters, set conditions, and customize how the component behaves.</span>
        </div>

        <!-- Advanced Features -->
        <div class="tip-slide">
            <span class="tip-icon">🎯</span>
            <span class="tip-text">Use Success/Failure paths to handle different outcomes. Green path for successful operations, red for error handling - making your workflows robust and reliable.</span>
        </div>
        <div class="tip-slide">
            <span class="tip-icon">📦</span>
            <span class="tip-text">Save your circuits as reusable packages. This lets you create complex workflows once and reuse them across different projects.</span>
        </div>

        <!-- Component Types -->
        <div class="tip-slide">
            <span class="tip-icon">🔄</span>
            <span class="tip-text">Trigger components (like webhooks or schedules) start your workflows. Action components perform tasks. Condition components make decisions based on data.</span>
        </div>
        <div class="tip-slide">
            <span class="tip-icon">🔍</span>
            <span class="tip-text">Use Filter components to process data, Transform components to modify content, and API components to interact with external services.</span>
        </div>

        <!-- Best Practices -->
        <div class="tip-slide">
            <span class="tip-icon">📝</span>
            <span class="tip-text">Name your components descriptively and organize them logically. Good organization makes workflows easier to understand and maintain.</span>
        </div>
        <div class="tip-slide">
            <span class="tip-icon">🔒</span>
            <span class="tip-text">Always test your workflows with sample data before activating them. Use the built-in testing tools to validate each step of your process.</span>
        </div>

        <!-- Advanced Workflows -->
        <div class="tip-slide">
            <span class="tip-icon">🔀</span>
            <span class="tip-text">Create parallel processes by connecting multiple components to one output. This allows you to perform several operations simultaneously.</span>
        </div>
        <div class="tip-slide">
            <span class="tip-icon">⚡</span>
            <span class="tip-text">Optimize performance by using caching components for repeated operations and cleanup components to manage resources efficiently.</span>
        </div>

        <!-- Troubleshooting -->
        <div class="tip-slide">
            <span class="tip-icon">🐛</span>
            <span class="tip-text">Use the debug panel to track data flow between components. This helps identify issues and understand how your workflow is processing information.</span>
        </div>
        <div class="tip-slide">
            <span class="tip-icon">💾</span>
            <span class="tip-text">Regularly save your work and export important workflows. You can also version your packages to track changes over time.</span>
        </div>

        <!-- Integration Tips -->
        <div class="tip-slide">
            <span class="tip-icon">🌐</span>
            <span class="tip-text">Connect to external services using API components. Store sensitive data like API keys in the secure credentials manager.</span>
        </div>
        <div class="tip-slide">
            <span class="tip-icon">📊</span>
            <span class="tip-text">Monitor your workflows using the analytics dashboard. Track execution times, success rates, and identify bottlenecks.</span>
        </div>
    </div>
    <button class="tip-nav prev">‹</button>
    <button class="tip-nav next">›</button>
</div>
<!-- End Tips Carousel -->